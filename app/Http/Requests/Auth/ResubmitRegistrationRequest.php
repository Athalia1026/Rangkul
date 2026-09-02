<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResubmitRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan user sudah login dan statusnya sedang ditolak
        return true;
        return $user && ($user->status === 'rejected' || $user->organization?->status === 'rejected');
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',

            'nama_lembaga'   => 'required|string|max:500',
            'tipe'           => 'required|string',
            'no_telp'        => 'required|string|max:255',
            'deskripsi'      => 'required|string',
            'kota'           => 'required|string|max:255',
            'alamat'         => 'required|string',
            'link_maps'      => 'nullable|string|max:500',
            'jumlah_anak'    => 'nullable|integer',
            'tahun_berdiri'  => 'nullable|integer',

            'sk_operasional' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'ktp_pj'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'foto_bangunan'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'foto_kegiatan'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ];
    }
}