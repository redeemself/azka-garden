<?php

namespace App\Http\Controllers;

use App\Models\Faq;

class FaqController extends Controller
{
    public function index()
    {
        // Ambil semua FAQ, urutkan sesuai order
        $faqs = Faq::orderBy('order')->get();

        // Galeri gambar otomatis (misal 1-50, sesuaikan lokasi file/folder)
        $galleryImages = [];
        for ($i = 1; $i <= 50; $i++) {
            $galleryImages[] = "images/Azka-garden{$i}.jpg";
        }

        // Kirim FAQ dan galeri ke view
        return view('faq', compact('faqs', 'galleryImages'));
    }
}
