<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SearchPageTest extends TestCase
{
    use DatabaseTransactions;

    /** Pencarian hanya menampilkan kampanye yang masih berjalan, jadi pastikan kampanye seed belum berakhir. */
    private function keepSembakoCampaignRunning(): void
    {
        Campaign::where('judul', 'Bantuan Sembako Anak Asuh')->update(['tanggal_selesai' => now()->addDays(7)]);
    }

    public function test_search_page_can_be_rendered(): void
    {
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('Temukan Campaign yang Ingin Anda Dukung');
        $response->assertSee('Pilihan Rangkul');
        $response->assertSee('pilihanRangkulTrack');
        $response->assertSee('Penggalangan Dana Lainnya');
    }

    public function test_search_page_with_query(): void
    {
        $this->keepSembakoCampaignRunning();
        $response = $this->get('/search?q=sembako');

        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian');
        $response->assertSee('Bantuan Sembako Anak Asuh');
    }

    public function test_search_page_with_sort(): void
    {
        $response = $this->get('/search?sort=mendesak');

        $response->assertStatus(200);
        $response->assertSee('Paling Mendesak');
        $response->assertSee('Terbaru');
    }

    public function test_search_results_page_can_be_rendered(): void
    {
        $response = $this->get('/search-results');

        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian');
        $response->assertSee('Penggalangan Dana');
        $response->assertSee('Organisasi');
        $response->assertSee('tabBtnPenggalangan');
        $response->assertSee('tabBtnOrganisasi');
        $response->assertSee('contentPenggalangan');
        $response->assertSee('contentOrganisasi');
    }

    public function test_organization_search_result_links_to_its_public_profile_without_login(): void
    {
        config(['scout.driver' => 'null']);

        $user = User::create([
            'nama' => 'Uji Organisasi',
            'email' => 'uji-organisasi-' . uniqid() . '@example.com',
            'password' => bcrypt('passwordbenar1'),
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

        $response = $this->get('/search-results?tab=organisasi');

        $response->assertStatus(200);
        $response->assertSee('href="' . route('donatur.panti.show', $organization->id) . '"', false);
        $response->assertSee($organization->nama_lembaga);

        $profileResponse = $this->get(route('donatur.panti.show', $organization->id));

        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Tentang Kami');
        $profileResponse->assertDontSee('data-donor-page data-login-url=', false);
    }

    public function test_search_results_page_with_query(): void
    {
        $this->keepSembakoCampaignRunning();
        $response = $this->get('/search-results?q=sembako');

        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian');
        $response->assertSee('Bantuan Sembako Anak Asuh');
        $this->assertMatchesRegularExpression(
            '/Menampilkan\s+\d+\s+hasil untuk/',
            strip_tags($response->getContent())
        );
    }

    public function test_donor_search_results_count_is_the_same_for_both_tabs(): void
    {
        $this->keepSembakoCampaignRunning();
        $organizationTabResponse = $this->get('/donatur/hasil-pencarian?q=sembako&tab=organisasi');
        $campaignTabResponse = $this->get('/donatur/hasil-pencarian?q=sembako&tab=penggalangan');

        $organizationTabResponse->assertOk();
        $campaignTabResponse->assertOk();
        preg_match('/Menampilkan\s+(\d+)\s+hasil untuk/', strip_tags($organizationTabResponse->getContent()), $organizationCount);
        preg_match('/Menampilkan\s+(\d+)\s+hasil untuk/', strip_tags($campaignTabResponse->getContent()), $campaignCount);

        $this->assertNotEmpty($organizationCount);
        $this->assertNotEmpty($campaignCount);
        $this->assertSame($organizationCount[1], $campaignCount[1]);
    }

    public function test_donor_search_defaults_to_organizations_when_campaigns_are_empty(): void
    {
        $response = $this->view('donatur.hasil-pencarian', [
            'campaigns' => collect(),
            'organizations' => collect([[
                'id' => 'organization-id',
                'image_url' => null,
                'nama_lembaga' => 'Panti Kasih',
                'deskripsi' => 'Lembaga uji',
                'kota' => 'Bandung',
            ]]),
            'query' => 'kasih',
        ]);

        $this->assertMatchesRegularExpression(
            '/<a href="[^"]*tab=organisasi"\s+aria-current="page"/',
            (string) $response
        );
        $response->assertSee('Panti Kasih');
        $response->assertDontSee('Kampanye belum ditemukan');
    }

    public function test_donor_search_result_tabs_show_zero_when_no_results_match(): void
    {
        config(['scout.driver' => 'null']);

        $response = $this->get('/donatur/hasil-pencarian?q=tidak-ada-hasil-' . uniqid());

        $response->assertStatus(200);
        $response->assertSee('aria-label="Jumlah hasil penggalangan dana: 0">0</span>', false);
        $response->assertSee('aria-label="Jumlah hasil organisasi: 0">0</span>', false);
    }

    public function test_donor_search_page_allows_empty_search_to_open_results_for_both_categories(): void
    {
        config(['scout.driver' => 'null']);

        $searchPage = $this->get('/donatur/cari');
        $searchPage->assertStatus(200);
        $searchPage->assertSee('data-allow-empty-search', false);

        $resultsPage = $this->get('/donatur/hasil-pencarian');
        $resultsPage->assertStatus(200);
        $resultsPage->assertSee('Penggalangan Dana');
        $resultsPage->assertSee('Organisasi');
        $resultsPage->assertSee('aria-label="Jumlah hasil penggalangan dana:', false);
        $resultsPage->assertSee('aria-label="Jumlah hasil organisasi:', false);
    }
}
