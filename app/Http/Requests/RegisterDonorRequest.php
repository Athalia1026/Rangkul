<?php

namespace App\Http\Requests;

use App\Support\IndonesianCities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterDonorRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,filter|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*[0-9]).+$/',
            ],

            // Data Profil Donatur (Table: donors)
            'tipe' => 'required|in:individu,komunitas,perusahaan',
            'no_telp' => 'required|string|max:255',
            'kota' => ['required', 'string', 'max:255', Rule::in(IndonesianCities::all())],
        ];
    }

    public function messages(): array
    {
        return [
            'kota.in' => 'Pilih kota dari daftar yang tersedia.',
        ];
    }
}
