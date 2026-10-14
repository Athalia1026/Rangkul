<?php

namespace Tests\Feature;

use App\Models\OrganizationDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegisterOrganizationUploadTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake(OrganizationDocument::DISK);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Uji Upload Organisasi',
            'email' => 'uji-org-' . uniqid() . '@example.com',
            'password' => 'passwordbaru1',
            'password_confirmation' => 'passwordbaru1',
            'nama_lembaga' => 'Panti Uji',
            'tipe' => 'Panti Asuhan',
            'no_telp' => '081234567890',
            'deskripsi' => 'Deskripsi uji.',
            'kota' => 'Jakarta',
            'alamat' => 'Jl. Uji No. 1',
            'bank' => 'BCA',
            'no_rekening' => '1234567890',
            'pemilik_rekening' => 'Panti Uji',
            'sk_operasional' => UploadedFile::fake()->create('sk.pdf', 100, 'application/pdf'),
            'ktp_pj' => UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf'),
            'foto_bangunan' => UploadedFile::fake()->image('bangunan.jpg'),
            'foto_kegiatan' => UploadedFile::fake()->image('kegiatan.jpg'),
        ], $overrides);
    }

    public function test_html_profile_photo_is_rejected(): void
    {
        $evil = UploadedFile::fake()->createWithContent('evil.html', '<html><script>alert(1)</script></html>');

        $this->postJson('/api/register/organization', $this->payload(['profile_photo' => $evil]))
            ->assertStatus(422)
            ->assertJsonValidationErrors('profile_photo');

        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_image_profile_photo_is_stored_without_exposing_path(): void
    {
        $response = $this->postJson('/api/register/organization', $this->payload([
            'profile_photo' => UploadedFile::fake()->image('logo.png'),
        ]));

        $response->assertCreated()->assertJsonMissingPath('data.profile_photo');

        $user = User::where('email', $response->json('data.email'))->firstOrFail();
        $this->assertMatchesRegularExpression('#^profiles/[A-Za-z0-9]{40}\.png$#', $user->profile_photo);
        Storage::disk('public')->assertExists($user->profile_photo);
    }
}
