<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrganizationCampaignCreationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        config(['scout.driver' => 'null']);
        Storage::fake('public');
    }

    public function test_organization_can_open_campaign_creation_page(): void
    {
        [$user] = $this->organization();
        Category::create(['name' => 'Pendidikan', 'icon' => 'fa-solid fa-book']);

        $this->actingAs($user, 'web')
            ->get(route('organisasi.kampanye.create'))
            ->assertOk()
            ->assertSee('Tambah Kampanye')
            ->assertSee('Pendidikan');
    }

    public function test_organization_can_submit_campaign_creation_form(): void
    {
        [$user, $organization] = $this->organization();
        $category = Category::create(['name' => 'Pendidikan', 'icon' => 'fa-solid fa-book']);

        $this->actingAs($user, 'web')
            ->post(route('organisasi.kampanye.store'), [
                'judul' => 'Kampanye Pendidikan',
                'deskripsi' => 'Bantuan perlengkapan belajar.',
                'tanggal_mulai' => now()->toDateString(),
                'tanggal_selesai' => now()->addMonth()->toDateString(),
                'target_dana' => 1000000,
                'id_categories' => $category->id,
                'foto_cover' => UploadedFile::fake()->image('cover.jpg'),
            ])
            ->assertRedirect(route('organisasi.kampanye'))
            ->assertSessionHas('success');

        $campaign = Campaign::where('judul', 'Kampanye Pendidikan')->firstOrFail();
        $this->assertSame($organization->id, $campaign->id_organisasi);
        $this->assertSame('menunggu', $campaign->status);
        Storage::disk('public')->assertExists($campaign->foto_cover);
    }

    /** @return array{0: User, 1: Organization} */
    private function organization(): array
    {
        $user = User::create([
            'nama' => 'Organisasi Uji',
            'email' => 'organisasi-' . uniqid() . '@example.com',
            'password' => Hash::make('passwordbenar1'),
            'account_type' => 'organisasi',
            'status' => 'aktif',
        ]);

        $organization = Organization::create([
            'user_id' => $user->id,
            'tipe' => 'Panti Asuhan',
            'nama_lembaga' => 'Panti Uji ' . uniqid(),
            'no_telp' => '081234567890',
            'deskripsi' => 'Lembaga uji',
            'kota' => 'Bandung',
            'alamat' => 'Jl. Uji',
            'verification_status' => 'disetujui',
        ]);

        return [$user, $organization];
    }
}
