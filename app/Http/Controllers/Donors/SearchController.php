<?php

namespace App\Http\Controllers\Donors;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function page(Request $request)
    {
        if (!$request->has('limit')) {
            $request->merge(['limit' => 30]);
        }

        if ($request->has('q')) {
            session(['last_search_query' => $request->query('q')]);
        }

        $payload = $this->search($request)->getData(true);

        return view('search', [
            'campaigns' => collect($payload['data']['campaigns'] ?? []),
            'query' => $payload['data']['query'] ?? '',
            'sort' => $payload['data']['sort'] ?? 'latest',
            'categories' => $request->routeIs('donatur.cari') ? Category::orderBy('name')->get() : collect(),
        ]);
    }

    public function results(Request $request)
    {
        if (!$request->has('limit')) {
            $request->merge(['limit' => 30]);
        }

        if ($request->has('q')) {
            session(['last_search_query' => $request->query('q')]);
        }

        $payload = $this->search($request)->getData(true);

        return view($request->routeIs('donatur.search.results') ? 'donatur.hasil-pencarian' : 'search_result', [
            'campaigns' => collect($payload['data']['campaigns'] ?? []),
            'organizations' => collect($payload['data']['organizations'] ?? []),
            'query' => $payload['data']['query'] ?? '',
            'sort' => $payload['data']['sort'] ?? 'latest',
        ]);
    }

    public function search(Request $request)
    {
        $request->validate(['category' => ['nullable', 'string', 'max:500']]);
        $keyword = trim((string) $request->query('q', ''));
        $sort = match ($request->query('sort', 'latest')) {
            'mendesak', 'urgent' => 'urgent',
            default => 'latest',
        };
        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min($limit, 20));
        $limit = max(1, min($limit, 50));

        $campaignQuery = Campaign::query()
            ->with(['organization', 'donations' => function ($query) {
                $query->where('status', 'sudah_bayar');
            }])
            ->where('status', 'aktif');

        $organizationQuery = Organization::query()
            ->where('verification_status', 'disetujui');

        if ($request->filled('category')) {
            $campaignQuery->where('id_categories', $request->query('category'));
        }

        if ($keyword !== '') {
            try {
                // Use Scout's shared API for both collection and Meilisearch drivers.
                $campaignIds = Campaign::search($keyword)->take(100)->keys()->toArray();
                $orgIds = Organization::search($keyword)->take(100)->keys()->toArray();

                $campaignQuery->whereIn('id', $campaignIds);
                $organizationQuery->whereIn('id', $orgIds);
            } catch (\Exception $e) {
                // Graceful fallback to SQL LIKE if Meilisearch is down
                $campaignQuery->where(function ($query) use ($keyword) {
                    $query->where('judul', 'like', "%{$keyword}%")
                        ->orWhere('deskripsi', 'like', "%{$keyword}%");
                });

                $organizationQuery->where(function ($query) use ($keyword) {
                    $query->where('nama_lembaga', 'like', "%{$keyword}%")
                        ->orWhere('tipe', 'like', "%{$keyword}%")
                        ->orWhere('kota', 'like', "%{$keyword}%")
                        ->orWhere('deskripsi', 'like', "%{$keyword}%");
                });
            }
        }

        $campaigns = $campaignQuery->get()->map(function ($campaign) {
            $targetDana = (int) $campaign->target_dana;
            $terkumpul = (int) $campaign->donations->sum('nominal');

            $persentase = $targetDana > 0
                ? min(100, round(($terkumpul / $targetDana) * 100, 1))
                : 0;

            $imageUrl = null;
            if ($campaign->foto_cover) {
                $imageUrl = filter_var($campaign->foto_cover, FILTER_VALIDATE_URL)
                    ? $campaign->foto_cover
                    : asset('storage/' . ltrim($campaign->foto_cover, '/'));
            }

            return [
                'id' => $campaign->id,
                'jenis' => 'campaign',
                'judul' => $campaign->judul,
                'deskripsi' => $campaign->deskripsi,
                'nama_organisasi' => $campaign->organization?->nama_lembaga ?? '-',
                'image_url' => $imageUrl,
                'target_dana' => $targetDana,
                'terkumpul' => $terkumpul,
                'persentase' => $persentase,
                'sisa_hari' => (int) $campaign->sisa_hari,
                'created_at' => $campaign->created_at?->toDateTimeString(),
                'status' => $campaign->status,
            ];
        });

        if ($sort === 'urgent') {
            $campaigns = $campaigns->sortBy(fn ($campaign) => (int) ($campaign['sisa_hari'] ?? 999999))->values();
        } else {
            $campaigns = $campaigns->sortByDesc('created_at')->values();
        }

        $campaigns = $campaigns->slice(0, $limit)->values();

        $organizations = $organizationQuery->with(['galleries' => function ($query) {
            $query->orderBy('display_order', 'asc')->limit(1);
        }])->get()->map(function ($organization) {
            return [
                'id' => $organization->id,
                'jenis' => 'organization',
                'nama_lembaga' => $organization->nama_lembaga,
                'tipe' => $organization->tipe,
                'kota' => $organization->kota,
                'deskripsi' => $organization->deskripsi,
                'image_url' => $organization->galleries->first()?->image_url ?? null,
                'created_at' => $organization->created_at?->toDateTimeString(),
                'verification_status' => $organization->verification_status,
            ];
        });

        if ($sort === 'latest') {
            $organizations = $organizations->sortByDesc('created_at')->values();
        } else {
            $organizations = $organizations->sortByDesc('created_at')->values();
        }

        $organizations = $organizations->slice(0, $limit)->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'query' => $keyword,
                'sort' => $sort,
                'campaigns' => $campaigns,
                'organizations' => $organizations,
            ]
        ]);
    }
}
