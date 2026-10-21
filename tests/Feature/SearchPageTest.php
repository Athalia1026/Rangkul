<?php

namespace Tests\Feature;

use App\Models\Campaign;
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

    public function test_search_results_page_with_query(): void
    {
        $this->keepSembakoCampaignRunning();
        $response = $this->get('/search-results?q=sembako');

        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian');
        $response->assertSee('Bantuan Sembako Anak Asuh');
    }
}

