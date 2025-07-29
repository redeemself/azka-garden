<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Tampilkan halaman utama blog dengan daftar artikel atau hasil pencarian.
     */
    public function index(Request $request)
    {
        $source = $request->get('source', 'web'); // 'web' atau 'scholar'
        $query = trim($request->get('q', ''));

        // Jika query kosong, tampilkan daftar artikel default
        if ($query === '') {
            $articles = $this->getDefaultArticles();
            return view('blog.index', compact('articles', 'source', 'query'));
        }

        $cacheKeyQuery = Str::slug($query);

        if ($source === 'web') {
            // Ambil seluruh artikel default
            $allArticles = $this->getDefaultArticles();

            // Filter artikel yang mengandung kata kunci di judul atau excerpt (case-insensitive)
            $articles = $allArticles->filter(function ($article) use ($query) {
                $needle = mb_strtolower($query);
                $title = mb_strtolower($article['title'] ?? '');
                $excerpt = mb_strtolower($article['excerpt'] ?? '');

                return str_contains($title, $needle) || str_contains($excerpt, $needle);
            })->values(); // reset index setelah filter

        } elseif ($source === 'scholar') {
            // Gunakan cache untuk data scholar agar tidak selalu hit API / load data statis
            $articles = Cache::remember("scholar_search_{$cacheKeyQuery}", now()->addMinutes(60), function () use ($query) {
                $scholarArticles = collect([
                    [
                        'title' => 'Azka Garden: Hortikultura Keluarga Pak Hendrik di Depok',
                        'excerpt' => 'Usaha hortikultura keluarga Pak Hendrik memadukan kios tanaman dan kanal edukasi digital.',
                        'url' => 'https://semuabis.com/azka-garden-bangunjiwo-0811-2821-369',
                        'date' => '2025',
                    ],
                    [
                        'title' => 'Jasa Lanskap dan Produksi Konten Berkebun Azka Garden',
                        'excerpt' => 'Azka Garden menyediakan layanan penataan taman dan edukasi berkebun melalui YouTube dan Facebook.',
                        'url' => 'https://www.youtube.com/channel/UCuAUD9jzepl1iay_eIlDgKw',
                        'date' => '2025',
                    ],
                    [
                        'title' => 'Lokasi dan Identitas Operasional Azka Garden di Depok',
                        'excerpt' => 'Kios inti beralamat di Jalan Raya KSU, Kelurahan Tirtajaya, Kota Depok dengan jam buka 24 jam.',
                        'url' => 'https://maps.app.goo.gl/j5AuLF1AZ3VVgovcA',
                        'date' => '2025',
                    ],
                    [
                        'title' => 'Produk Tanaman dan Media Tanam Siap Pakai Azka Garden',
                        'excerpt' => 'Penjualan Philodendron, Caladium, pucuk merah, bibit buah, dan media tanam siap pakai.',
                        'url' => 'https://www.youtube.com/watch?v=uZ-w0thuwk0',
                        'date' => '2025',
                    ],
                    [
                        'title' => 'Keberadaan Digital Azka Garden: YouTube, Facebook, Instagram',
                        'excerpt' => 'Kanal edukasi dan media sosial resmi dengan ribuan pelanggan dan pengikut aktif.',
                        'url' => 'https://www.instagram.com/azka_garden/',
                        'date' => '2025',
                    ],
                    [
                        'title' => 'Variasi Nama Azka Garden di Kota Lain',
                        'excerpt' => 'Penggunaan nama Azka Garden oleh pihak tak terafiliasi di Pekanbaru, Batam, dan Tangerang.',
                        'url' => 'https://facebook.com',
                        'date' => '2025',
                    ],
                    [
                        'title' => 'Sinergi Luring dan Daring di Bisnis Azka Garden',
                        'excerpt' => 'Kredibilitas lapak fisik dan edukasi digital saling mendukung keberhasilan usaha hortikultura.',
                        'url' => 'https://semuabis.com',
                        'date' => '2025',
                    ],
                    [
                        'title' => 'Daftar Referensi & Sumber Lengkap Azka Garden',
                        'excerpt' => 'Kumpulan tautan ke situs, kanal media sosial, dan sumber hukum terkait Azka Garden.',
                        'url' => 'https://semuabis.com',
                        'date' => '2025',
                    ],
                ]);

                // Filter berdasarkan query
                $needle = mb_strtolower($query);
                return $scholarArticles->filter(function ($article) use ($needle) {
                    $title = mb_strtolower($article['title'] ?? '');
                    $excerpt = mb_strtolower($article['excerpt'] ?? '');
                    return str_contains($title, $needle) || str_contains($excerpt, $needle);
                })->values();
            });

        } else {
            $articles = collect();
        }

        return view('blog.index', compact('articles', 'source', 'query'));
    }

    /**
     * Method untuk pencarian artikel blog (route /blog/search).
     * Fungsi ini hanya mengarahkan ke index dengan parameter request.
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if ($query === '') {
            return redirect()->route('blog.index');
        }

        return $this->index($request);
    }

    /**
     * Daftar artikel default statis untuk ditampilkan saat tidak ada pencarian.
     */
    private function getDefaultArticles()
    {
        return collect([
            [
                'title' => 'Cara Merawat Philodendron Agar Tumbuh Subur',
                'excerpt' => 'Pelajari tips perawatan philodendron dengan mudah, mulai dari penyiraman hingga pemupukan yang tepat.',
                'url' => 'https://www.youtube.com/watch?v=uZ-w0thuwk0',
                'date' => '14 Juli 2025'
            ],
            [
                'title' => 'Mengenal Media Tanam Organik untuk Tanaman Hias',
                'excerpt' => 'Media tanam organik apa saja yang cocok dan bagaimana cara membuatnya sendiri di rumah?',
                'url' => 'https://www.youtube.com/watch?v=CbeRFKZdqN0',
                'date' => '10 Juli 2025'
            ],
            [
                'title' => 'Inspirasi Desain Taman Minimalis di Rumah',
                'excerpt' => 'Tips menata taman kecil di halaman rumah agar terlihat asri dan nyaman untuk bersantai.',
                'url' => 'https://semuabis.com/azka-garden-bangunjiwo-0811-2821-369',
                'date' => '5 Juli 2025'
            ],
            [
                'title' => 'Panduan Stek Batang untuk Pemula',
                'excerpt' => 'Langkah mudah stek batang tanaman hias agar cepat tumbuh dan berakar kuat.',
                'url' => 'https://www.youtube.com/channel/UCuAUD9jzepl1iay_eIlDgKw',
                'date' => '1 Juli 2025'
            ],
            [
                'title' => 'Teknik Pemangkasan Tanaman Agar Lebih Sehat',
                'excerpt' => 'Cara tepat memangkas tanaman untuk merangsang pertumbuhan dan menghindari penyakit.',
                'url' => 'https://www.azkamultikarya.com/layanan-kami/jasa-pertamanan-gardening-service/',
                'date' => '20 Juni 2025'
            ],
            [
                'title' => 'Panduan Memilih Pot yang Tepat untuk Tanaman Hias',
                'excerpt' => 'Memilih pot dengan bahan dan ukuran ideal agar tanaman tumbuh optimal.',
                'url' => 'https://www.facebook.com/people/Azka-Garden-Indonesia/100063831022523/',
                'date' => '15 Juni 2025'
            ],
            [
                'title' => 'Tips Merawat Tanaman Buah di Halaman Rumah',
                'excerpt' => 'Langkah mudah merawat tanaman buah agar hasil panen maksimal.',
                'url' => 'https://www.facebook.com/groups/1609898615836821/posts/2832471493579521/',
                'date' => '8 Juni 2025'
            ],
            [
                'title' => 'Manfaat dan Cara Membuat Kompos Organik Sendiri',
                'excerpt' => 'Pelajari pembuatan kompos untuk media tanam yang subur dan ramah lingkungan.',
                'url' => 'https://www.facebook.com/100083077279733/videos/perumahan-azka-garden-dgan-luas-tanah-206m%C2%B2-yang-berada-di-jalan-unggas-simpang-/1058432275484393/',
                'date' => '2 Juni 2025'
            ],
        ]);
    }
}
