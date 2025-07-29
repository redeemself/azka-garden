<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Produk terbaru: urut dari yang paling baru
        $newestProducts = Product::orderBy('created_at', 'desc')->take(6)->get();

        // Produk rekomendasi: ambil 9 produk terbaru saja (lebih efisien, sesuai kebutuhan view)
        $products = Product::latest()->take(9)->get();

        // Produk paling disukai (contoh random, bisa pakai likes terbanyak)
        $mostLikedProducts = Product::inRandomOrder()->take(6)->get();

        // Produk unggulan (jika diperlukan)
        $featuredProducts = Product::where('is_featured', true)->take(8)->get();

        // Testimoni (jika diperlukan)
        $testimonials = Testimonial::all();

        return view('home', compact(
            'newestProducts',
            'products',
            'mostLikedProducts',
            'featuredProducts',
            'testimonials'
        ));
    }
}
