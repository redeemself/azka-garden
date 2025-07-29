<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan otorisasi sesuai kebutuhan (misal: hanya admin)
    }

    public function rules()
    {
        return [
            'name'        => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'weight'      => 'required|numeric|min:0',
            'image_url'   => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'status'      => 'required|boolean',
            // Tambahkan validasi promo jika ingin validasi promo_code di produk
            'promo_code'  => 'nullable|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib diisi.',
            'price.required'       => 'Harga wajib diisi.',
            // Tambahkan pesan lain sesuai kebutuhan...
        ];
    }
}