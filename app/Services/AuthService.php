<?php

namespace App\Services;

use App\Models\BankAccount;
use App\Models\Donor;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

            return [
                'user' => $user->load('donor'),
                'token' => $user->createToken('rangkul-donor-token')->plainTextToken,
            ];
        });
    }

    public function registerOrganization(Request $request): array
    {
        $uploadedFiles = [];

        try {
            DB::beginTransaction();

            $result = $this->registerOrganizationData($request);
            $user = $result['user'];
            $token = $result['token'];
            $uploadedFiles = $result['uploaded_files'];

            DB::commit();

            $user->load(['organization.documents', 'organization.bankAccount']);

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

    private function registerOrganizationData(Request $request): array
    {
        $uploadedFiles = [];

        $photoPath = $this->storeProfilePhotoIfPresent($request);
        if ($photoPath !== null) {
            $uploadedFiles[] = $photoPath;
        }

        $user = $this->createOrganizationUser($request, $photoPath);
        $organization = $this->createOrganizationProfile($request, $user);

        $uploadedFiles = array_merge(
            $uploadedFiles,
            $this->storeOrganizationDocuments($request, $organization)
        );

        $this->createOrganizationBankAccount($request, $organization);

        return [
            'user' => $user,
            'token' => $user->createToken('rangkul-org-token')->plainTextToken,
            'uploaded_files' => $uploadedFiles,
        ];
    }

    private function storeProfilePhotoIfPresent(Request $request): ?string
    {
        if (!$request->hasFile('profile_photo')) {
            return null;
        }

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

    private function storeOrganizationDocuments(Request $request, Organization $organization): array
    {
        $documentRows = $this->buildOrganizationDocumentRows($request, $organization);

        if (empty($documentRows)) {
            return [];
        }

        OrganizationDocument::insert($documentRows);

        return array_column($documentRows, 'lokasi_file');
    }

    private function buildOrganizationDocumentRows(Request $request, Organization $organization): array
    {
        $documentRows = [];

        foreach ($this->organizationDocumentConfig() as $type => $folder) {
            if (!$request->hasFile($type)) {
                continue;
            }

            $file = $request->file($type);
            $path = $file->store($folder, 'public');

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

    private function cleanupUploadedFiles(array $uploadedFiles): void
    {
        $paths = array_values(array_filter($uploadedFiles, fn ($file) => is_string($file) && $file !== ''));

        if ($paths === []) {
            return;
        }

        Storage::disk('public')->delete($paths);
    }

    public function login(array $credentials): array
    {
        $user = $this->findUserByEmail($credentials['email']);
        $this->ensureCredentialsAreValid($user, $credentials['password']);
        $this->ensureAccountIsActive($user);

        $blockedResponse = $this->resolveOrganizationVerificationBlock($user);
        if ($blockedResponse !== null) {
            return $blockedResponse;
        }

        $this->loadUserRelation($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->buildSuccessfulLoginResponse($user, $token);
    }

    private function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    private function ensureCredentialsAreValid(?User $user, string $password): void
    {
        if (!$user || !Hash::check($password, $user->password)) {
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
        $user = $this->findUserByEmail($validated['email']);

        $this->ensureResubmissionCredentialsAreValid($user, $validated['password']);

        $organization = $this->resolveRejectedOrganization($user);
        $this->updateRejectedOrganizationProfile($organization, $validated);

        $existingDocuments = $this->getOrganizationDocumentsByOrganizationId($organization->id);
        $this->resubmitOrganizationDocuments($request, $organization, $existingDocuments);

        return [
            'status' => 'success',
            'message' => 'Pendaftaran ulang berhasil dikirimkan. Silakan tunggu verifikasi admin.',
            'data' => $organization->fresh(),
        ];
    }

    private function ensureResubmissionCredentialsAreValid(?User $user, string $password): void
    {
        if (!$user || !Hash::check($password, $user->password)) {
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
            $newPath = $file->store($folder, 'public');
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
            if (Storage::disk('public')->exists($targetDocument->lokasi_file)) {
                Storage::disk('public')->delete($targetDocument->lokasi_file);
            }

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
