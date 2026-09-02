<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function store(Request $request)
    {
        $organization = $request->user()->organization;

        // Validasi: Organisasi wajib terverifikasi 'disetujui' terlebih dahulu
        if ($organization->verification_status !== 'disetujui') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Organisasi Anda belum terverifikasi oleh Admin.'
            ], 403);
        }

        $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_dana'     => 'required|numeric|min:10000',
            'id_categories'   => 'required|exists:categories,id',
            'foto_cover'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('foto_cover')->store('campaigns/covers', 'public');

        $campaign = Campaign::create([
            'id_organisasi'   => $organization->id,
            'judul'           => $request->judul,
            'deskripsi'       => $request->deskripsi,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'target_dana'     => $request->target_dana,
            'id_categories'   => $request->id_categories,
            'foto_cover'      => $path,
            'status'          => 'menunggu', // Default menunggu verifikasi admin
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Campaign berhasil dibuat dan sedang menunggu verifikasi admin.',
            'data'    => $campaign
        ], 201);
    }
}