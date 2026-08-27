<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Data Akun Utama (Table: users)
            'nama' => 'required|string|max:255', // Nama penanggung jawab / akun
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',

            // Data Profil Organisasi (Table: organizations)
            'nama_lembaga' => 'required|string|max:500',
            'tipe' => 'required|string', // Panti Asuhan, Yayasan, Sekolah, dll.
            'no_telp' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kota' => 'required|string|max:255',
            'alamat' => 'required|string',
            'link_maps' => 'nullable|string|max:500',
            'jumlah_anak' => 'nullable|integer',
            'tahun_berdiri' => 'nullable|integer',
        ];
    }
}
