<?php

namespace App\Http\Controllers\Donors;

use App\Models\Campaign;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignController extends Controller
{
    /**
     * Show detail page for a single campaign.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch campaign with its organization and related data
        $campaign = Campaign::with(['organization', 'donations' => function ($q) {
            $q->where('status', 'sudah_bayar');
        }])->where('id', $id)->where('status', 'aktif')->firstOrFail();

        // Prepare data similar to the search results transformation
        $targetDana = (int) $campaign->target_dana;
        $terkumpul = (int) $campaign->donations->sum('nominal');
        $persentase = $targetDana > 0 ? min(100, round(($terkumpul / $targetDana) * 100, 1)) : 0;

        $imageUrl = null;
        if ($campaign->foto_cover) {
            $imageUrl = filter_var($campaign->foto_cover, FILTER_VALIDATE_URL)
                ? $campaign->foto_cover
                : asset('storage/' . ltrim($campaign->foto_cover, '/'));
        }

        // Organization data
        $organization = $campaign->organization;

        // Doa & Harapan — ambil dari donations yang punya note
        $comments = $campaign->donations()
            ->where('status', 'sudah_bayar')
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->with(['donor.user'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($don) {
                $nama = 'Anonim';
                if (!$don->anonim && $don->donor && $don->donor->user) {
                    $nama = $don->donor->user->nama;
                }
                return [
                    'nama'   => $nama,
                    'detail' => $don->created_at ? $don->created_at->diffForHumans() : '',
                    'pesan'  => $don->note,
                    'avatar' => null, // fallback ke UI Avatars di view
                ];
            });

        // Other campaigns from the same organization (excluding current)
        $otherCampaigns = Campaign::with(['organization'])
            ->where('id_organisasi', $organization->id ?? null)
            ->where('id', '!=', $campaign->id)
            ->where('status', 'aktif')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get()
            ->map(function ($c) {
                $target = (int) $c->target_dana;
                $collected = (int) $c->donations()->where('status', 'sudah_bayar')->sum('nominal');
                $percent = $target > 0 ? min(100, round(($collected / $target) * 100, 1)) : 0;
                $img = $c->foto_cover ? (filter_var($c->foto_cover, FILTER_VALIDATE_URL) ? $c->foto_cover : asset('storage/' . ltrim($c->foto_cover, '/'))) : null;
                return [
                    'id' => $c->id,
                    'judul' => $c->judul,
                    'nama_organisasi' => $c->organization->nama_lembaga ?? '-',
                    'image_url' => $img,
                    'terkumpul' => $collected,
                    'persentase' => $percent,
                ];
            });

        $campaignData = [
            'id' => $campaign->id,
            'judul' => $campaign->judul,
            'deskripsi' => $campaign->deskripsi,
            'kategori' => $campaign->kategori ?? 'Kebutuhan Pangan',
            'image_url' => $imageUrl,
            'target_dana' => $targetDana,
            'terkumpul' => $terkumpul,
            'persentase' => $persentase,
            'sisa_hari' => (int) $campaign->sisa_hari,
            'donatur' => $campaign->donations()->where('status', 'sudah_bayar')->count(),
            'kebutuhan' => $campaign->kebutuhan ?? [], // assumes JSON column
        ];

        $searchQuery = (string) request('q', session('last_search_query', ''));
        if (request()->has('q')) {
            session(['last_search_query' => request('q')]);
        }

        return view('campaign_detail', [
            'campaign' => $campaignData,
            'organization' => $organization,
            'comments' => $comments,
            'otherCampaigns' => $otherCampaigns,
            'searchQuery' => $searchQuery,
        ]);
    }

    /**
     * Show all prayers / doa & harapan for a campaign.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\Response
     */
    public function prayers($id)
    {
        $campaign = Campaign::where('id', $id)->where('status', 'aktif')->firstOrFail();

        $prayers = $campaign->donations()
            ->where('status', 'sudah_bayar')
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->with(['donor.user'])
            ->latest()
            ->get()
            ->map(function ($don) {
                $isAnonim = (bool) $don->anonim;
                $user = $don->donor?->user;
                $nama = 'Anonim';
                $avatar = null;

                if (!$isAnonim && $user) {
                    $nama = $user->nama ?: 'Donatur';
                    if ($user->profile_photo) {
                        $avatar = filter_var($user->profile_photo, FILTER_VALIDATE_URL)
                            ? $user->profile_photo
                            : asset('storage/' . ltrim($user->profile_photo, '/'));
                    } else {
                        $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=d8f0e2&color=05522d&bold=true';
                    }
                }

                return [
                    'nama' => $nama,
                    'is_anonim' => $isAnonim || !$user,
                    'avatar' => $avatar,
                    'nominal' => (int) $don->nominal,
                    'waktu' => $don->created_at ? $don->created_at->diffForHumans() : '',
                    'note' => $don->note,
                ];
            });

        $searchQuery = (string) request('q', session('last_search_query', ''));

        return view('doa_harapan', [
            'campaign' => $campaign,
            'prayers' => $prayers,
            'searchQuery' => $searchQuery,
        ]);
    }
}

