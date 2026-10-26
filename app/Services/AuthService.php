<?php

namespace App\Services;

use App\Services\NotificationService;
use App\Models\BankAccount;
use App\Models\Donor;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function registerDonor(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'nama' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'account_type' => 'donatur',
                'status' => 'aktif',
            ]);

            Donor::create([
                'user_id' => $user->id,
                'tipe' => $data['tipe'],
                'no_telp' => $data['no_telp'],
                'kota' => $data['kota'],
            ]);

            app(ActivityLogService::class)->log('register', 'auth', 'Mendaftar sebagai donatur', $user, null, null, $user);

            return [
                'user' => $user->load('donor'),
                'token' => $user->createToken('rangkul-donor-token')->plainTextToken,
            ];
        });
    }

    public function registerOrganization(Request $request): array
    {
        // Path file yang sudah tersimpan, dikelompokkan per disk, agar bisa dihapus bila registrasi gagal di tengah jalan.
        $uploadedFiles = [];

        try {
            DB::beginTransaction();

            $result = $this->registerOrganizationData($request, $uploadedFiles);
            $user = $result['user'];
            $token = $result['token'];

            DB::commit();

            $user->load(['organization.documents', 'organization.bankAccount']);
            app(ActivityLogService::class)->log('register', 'auth', 'Mendaftar sebagai organisasi', $user, null, null, $user);
            app(NotificationService::class)->organizationRegistered($user->organization);

            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $this->cleanupUploadedFiles($uploadedFiles);
            throw $e;
        }
    }

    private function registerOrganizationData(Request $request, array &$uploadedFiles): array
    {
        $photoPath = $this->storeProfilePhotoIfPresent($request);
        if ($photoPath !== null) {
            $uploadedFiles['public'][] = $photoPath;
        }

        $user = $this->createOrganizationUser($request, $photoPath);
        $organization = $this->createOrganizationProfile($request, $user);

        $this->storeOrganizationDocuments($request, $organization, $uploadedFiles);

        $this->createOrganizationBankAccount($request, $organization);

        return [
            'user' => $user,
            'token' => $user->createToken('rangkul-org-token')->plainTextToken,
        ];
    }

    private function storeProfilePhotoIfPresent(Request $request): ?string
    {
        if (!$request->hasFile('profile_photo')) {
            return null;
        }

        // File sudah lolos validasi image|mimes di RegisterOrganizationRequest,
        // jadi ekstensi hasil tebakan isi file hanya bisa jpg/jpeg/png/webp.
        return $request->file('profile_photo')->store('profiles', 'public');
    }

    private function createOrganizationUser(Request $request, ?string $photoPath): User
    {
        return User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo' => $photoPath,
            'account_type' => 'organisasi',
            'status' => 'aktif',
        ]);
    }

    private function createOrganizationProfile(Request $request, User $user): Organization
    {
        return Organization::create([
            'user_id' => $user->id,
            'nama_lembaga' => $request->nama_lembaga,
            'tipe' => $request->tipe,
            'no_telp' => $request->no_telp,
            'deskripsi' => $request->deskripsi,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
            'link_maps' => $request->link_maps,
            'jumlah_anak' => $request->jumlah_anak,
            'tahun_berdiri' => $request->tahun_berdiri,
        ]);
    }

    private function storeOrganizationDocuments(Request $request, Organization $organization, array &$uploadedFiles): void
    {
        $documentRows = $this->buildOrganizationDocumentRows($request, $organization, $uploadedFiles);

        if (empty($documentRows)) {
            return;
        }

        OrganizationDocument::insert($documentRows);
    }

    private function buildOrganizationDocumentRows(Request $request, Organization $organization, array &$uploadedFiles): array
    {
        $documentRows = [];

        foreach ($this->organizationDocumentConfig() as $type => $folder) {
            if (!$request->hasFile($type)) {
                continue;
            }

            $file = $request->file($type);
            $path = $file->store($folder, OrganizationDocument::DISK);
            $uploadedFiles[OrganizationDocument::DISK][] = $path;

            $documentRows[] = [
                'id' => (string) Str::uuid(),
                'id_organisasi' => $organization->id,
                'nama_file' => $file->getClientOriginalName(),
                'lokasi_file' => $path,
                'status' => 'menunggu',
                'uploaded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $documentRows;
    }

    private function organizationDocumentConfig(): array
    {
        return [
            'sk_operasional' => 'documents/sk',
            'ktp_pj' => 'documents/ktp',
            'foto_bangunan' => 'documents/bangunan',
            'foto_kegiatan' => 'documents/kegiatan',
        ];
    }

    private function createOrganizationBankAccount(Request $request, Organization $organization): void
    {
        BankAccount::create([
            'id_organisasi' => $organization->id,
            'bank' => $request->bank,
            'no_rekening' => $request->no_rekening,
            'pemilik_rekening' => $request->pemilik_rekening,
            'status_verifikasi' => 'menunggu',
        ]);
    }

    /**
     * @param array<string, string[]> $uploadedFiles path file per nama disk
     */
    private function cleanupUploadedFiles(array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $disk => $files) {
            $paths = array_values(array_filter($files, fn ($file) => is_string($file) && $file !== ''));

            if ($paths !== []) {
                Storage::disk($disk)->delete($paths);
            }
        }
    }

    public function login(array $credentials): array
    {
        $lockKey = $this->loginLockKey($credentials['email']);
        $this->ensureAccountIsNotLocked($lockKey);

        $user = $this->findUserByEmail($credentials['email']);

        try {
            $this->ensureCredentialsAreValid($user, $credentials['password']);
        } catch (ValidationException $exception) {
            RateLimiter::hit($lockKey, config('security.login_lockout_seconds'));
            throw $exception;
        }

        RateLimiter::clear($lockKey);
        $this->ensureAccountIsActive($user);

        $blockedResponse = $this->resolveOrganizationVerificationBlock($user);
        if ($blockedResponse !== null) {
            return $blockedResponse;
        }

        $this->loadUserRelation($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->buildSuccessfulLoginResponse($user, $token);
    }

    /**
     * Kunci per email (bukan per IP): menghentikan brute force yang memakai banyak IP.
     * Berlaku juga untuk email yang tidak terdaftar, sehingga penguncian tidak membocorkan
     * email mana yang punya akun.
     */
    private function loginLockKey(string $email): string
    {
        return 'login-lock:' . sha1(Str::lower(trim($email)));
    }

    private function ensureAccountIsNotLocked(string $lockKey): void
    {
        if (!RateLimiter::tooManyAttempts($lockKey, config('security.login_max_attempts'))) {
            return;
        }

        $minutes = (int) ceil(RateLimiter::availableIn($lockKey) / 60);

        throw ValidationException::withMessages([
            'email' => ["Terlalu banyak percobaan login gagal. Coba lagi dalam {$minutes} menit."],
        ])->status(429);
    }

    private function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Cek password dengan waktu proses yang setara untuk email terdaftar maupun tidak:
     * tanpa ini, respons yang lebih cepat untuk email tak terdaftar membocorkan daftar akun.
     */
    private function passwordMatches(?User $user, string $password): bool
    {
        if (!$user) {
            Hash::make($password);

            return false;
        }

        return Hash::check($password, $user->password);
    }

    private function ensureCredentialsAreValid(?User $user, string $password): void
    {
        if (!$this->passwordMatches($user, $password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }
    }

    private function ensureAccountIsActive(User $user): void
    {
        if ($user->status === 'nonaktif') {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda dinonaktifkan. Hubungi admin.'],
            ]);
        }

        // Akun yang dihapus permanen tetap tersimpan (status 'dihapus') tetapi tidak bisa login lagi.
        if ($user->status === 'dihapus') {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda telah dihapus. Hubungi admin.'],
            ]);
        }
    }

    private function resolveOrganizationVerificationBlock(User $user): ?array
    {
        if (!$user->organization) {
            return null;
        }

        $status = $user->organization->verification_status;

        if ($status === 'ditolak') {
            return [
                'message' => 'Pendaftaran organisasi Anda ditolak. Silakan ajukan ulang dokumen.',
                'code' => 'ORGANIZATION_REJECTED',
                'user' => $user,
                'redirect_url' => $user->organizationRejectedUrl(),
                'status' => 403,
            ];
        }

        if ($status === 'menunggu' || $status === 'pending') {
            return [
                'message' => 'Akun berhasil diverifikasi kredensialnya, namun organisasi Anda masih menunggu verifikasi oleh Admin.',
                'code' => 'ORGANIZATION_UNVERIFIED',
                'user' => $user,
                'status' => 403,
            ];
        }

        return null;
    }

    private function loadUserRelation(User $user): void
    {
        if ($user->account_type === 'donatur') {
            $user->load('donor');
            return;
        }

        if ($user->account_type === 'organisasi') {
            $user->load('organization');
            return;
        }

        if ($user->account_type === 'admin') {
            $user->load('admin');
        }
    }

    private function buildSuccessfulLoginResponse(User $user, string $token): array
    {
        return [
            'message' => 'Login berhasil.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'token' => $token,
            'user' => $user,
            'status' => 200,
        ];
    }

    public function me($user): object
    {
        if ($user->account_type === 'donatur') {
            $user->load('donor');
        } elseif ($user->account_type === 'organisasi') {
            $user->load('organization');
        } elseif ($user->account_type === 'admin') {
            $user->load('admin');
        }

        return $user;
    }

    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function resubmit(Request $request): array
    {
        $validated = $request->validated();

        // Endpoint ini memeriksa password tanpa login, jadi ikut kunci per email yang sama dengan
        // login: tanpa itu, endpoint ini bisa dipakai menebak password akun mana pun tanpa batas.
        $lockKey = $this->loginLockKey($validated['email']);
        $this->ensureAccountIsNotLocked($lockKey);

        $user = $this->findUserByEmail($validated['email']);

        try {
            $this->ensureResubmissionCredentialsAreValid($user, $validated['password']);
        } catch (ValidationException $exception) {
            RateLimiter::hit($lockKey, config('security.login_lockout_seconds'));
            throw $exception;
        }

        RateLimiter::clear($lockKey);

        $organization = $this->resolveRejectedOrganization($user);
        $this->updateRejectedOrganizationProfile($organization, $validated);
        $this->updateRejectedOrganizationBankAccount($organization, $validated, $user);

        $existingDocuments = $this->getOrganizationDocumentsByOrganizationId($organization->id);
        $this->resubmitOrganizationDocuments($request, $organization, $existingDocuments);
        app(NotificationService::class)->organizationRegistered($organization->fresh(), true);

        return [
            'status' => 'success',
            'message' => 'Pendaftaran ulang berhasil dikirimkan. Silakan tunggu verifikasi admin.',
            'data' => $organization->fresh(),
        ];
    }

    /**
     * Akun non-organisasi selalu dianggap kredensial salah (pesan yang sama), sehingga respons
     * tidak membedakan "password benar tapi bukan organisasi" dari "password salah".
     */
    private function ensureResubmissionCredentialsAreValid(?User $user, string $password): void
    {
        $candidate = $user?->organization ? $user : null;

        if (!$this->passwordMatches($candidate, $password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial email atau password tidak valid.'],
            ]);
        }
    }

    private function resolveRejectedOrganization(?User $user): Organization
    {
        if (!$user || !$user->organization) {
            throw ValidationException::withMessages([
                'email' => ['Data organisasi tidak ditemukan.'],
            ]);
        }

        $organization = $user->organization;

        if ($organization->verification_status !== 'ditolak') {
            throw ValidationException::withMessages([
                'email' => ['Akses ditolak. Fitur pengajuan ulang hanya untuk pendaftaran yang ditolak.'],
            ]);
        }

        return $organization;
    }

    private function updateRejectedOrganizationProfile(Organization $organization, array $validated): void
    {
        $organization->fill([
            'nama_lembaga' => $validated['nama_lembaga'],
            'tipe' => $validated['tipe'],
            'no_telp' => $validated['no_telp'],
            'deskripsi' => $validated['deskripsi'],
            'kota' => $validated['kota'],
            'alamat' => $validated['alamat'],
            'link_maps' => $validated['link_maps'] ?? null,
            'jumlah_anak' => $validated['jumlah_anak'] ?? null,
            'tahun_berdiri' => $validated['tahun_berdiri'] ?? null,
            'verification_status' => 'menunggu',
            'alasan_penolakan' => null,
        ]);

        $organization->save();
    }

    private function getOrganizationDocumentsByOrganizationId(string $organizationId): Collection
    {
        return OrganizationDocument::where('id_organisasi', $organizationId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Rekening tujuan pencairan dana: perubahannya wajib tercatat di audit trail. Pengajuan ulang
     * berjalan tanpa sesi login, sehingga pencatatan otomatis model (butuh user login) tidak jalan
     * dan aktornya dicatat manual di sini.
     */
    private function updateRejectedOrganizationBankAccount(Organization $organization, array $validated, User $actor): void
    {
        $bankAccount = BankAccount::where('id_organisasi', $organization->id)->first();
        if (!$bankAccount) {
            return;
        }

        $before = $bankAccount->only(['bank', 'no_rekening', 'pemilik_rekening', 'status_verifikasi']);

        $bankAccount->update([
            'bank' => $validated['bank'],
            'no_rekening' => $validated['no_rekening'],
            'pemilik_rekening' => $validated['pemilik_rekening'],
            'status_verifikasi' => 'menunggu',
        ]);

        $changes = collect($bankAccount->getChanges())->except(['updated_at']);
        if ($changes->isEmpty()) {
            return;
        }

        app(ActivityLogService::class)->log(
            'update',
            'bank_account',
            'Memperbarui rekening lembaga saat pengajuan ulang pendaftaran',
            $bankAccount,
            array_intersect_key($before, $changes->all()),
            $changes->all(),
            $actor
        );
    }

    private function resubmitOrganizationDocuments(Request $request, Organization $organization, $existingDocuments): void
    {
        $fileFields = [
            'sk_operasional' => 'sk',
            'ktp_pj' => 'ktp',
            'foto_bangunan' => 'bangunan',
            'foto_kegiatan' => 'kegiatan',
        ];

        $documentMap = $this->mapDocumentsByKeyword($existingDocuments);

        foreach ($fileFields as $field => $keyword) {
            if (!$request->hasFile($field)) {
                continue;
            }

            $file = $request->file($field);
            $folder = 'documents/' . $keyword;
            $newPath = $file->store($folder, OrganizationDocument::DISK);
            $targetDocument = $documentMap[$keyword] ?? null;

            $this->replaceDocumentFile($targetDocument, $organization->id, $newPath, $file);
        }
    }

    private function mapDocumentsByKeyword($documents): array
    {
        $map = [];

        foreach ($documents as $document) {
            foreach (['sk', 'ktp', 'bangunan', 'kegiatan'] as $keyword) {
                if (str_contains(strtolower($document->lokasi_file), $keyword)) {
                    $map[$keyword] = $document;
                    break;
                }
            }
        }

        return $map;
    }

    private function replaceDocumentFile(?OrganizationDocument $targetDocument, string $organizationId, string $newPath, $file): void
    {
        if ($targetDocument) {
            Storage::disk(OrganizationDocument::DISK)->delete($targetDocument->lokasi_file);

            $targetDocument->update([
                'lokasi_file' => $newPath,
                'nama_file' => $file->getClientOriginalName(),
                'status' => 'menunggu',
                'alasan_penolakan' => null,
                'uploaded_at' => now(),
            ]);

            return;
        }

        $rejectedDocument = OrganizationDocument::where('id_organisasi', $organizationId)
            ->where('status', 'ditolak')
            ->first();

        if ($rejectedDocument) {
            $rejectedDocument->update([
                'lokasi_file' => $newPath,
                'nama_file' => $file->getClientOriginalName(),
                'status' => 'menunggu',
                'alasan_penolakan' => null,
                'uploaded_at' => now(),
            ]);
        }
    }
}
