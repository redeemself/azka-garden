<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Banner;
use App\Models\Promotion;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\Testimonial;
use App\Models\Contact;
use App\Models\Category; // <-- Tambahkan model kategori
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class PublicController extends Controller
{
    public function home(): View
    {
        $newestProducts = Product::orderBy('created_at', 'desc')->take(8)->get();
        $recommendedProducts = Product::where('is_featured', 1)->inRandomOrder()->take(8)->get();
        $mostLikedProducts = Product::withCount('likes')->orderByDesc('likes_count')->take(8)->get();
        $featuredProducts = Product::where('is_featured', 1)->take(8)->get();
        $testimonials = Testimonial::latest()->take(6)->get();

        return view('home', compact(
            'newestProducts',
            'recommendedProducts',
            'mostLikedProducts',
            'featuredProducts',
            'testimonials'
        ));
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        if (Contact::where('email', $validated['email'])->exists()) {
            return redirect()->route('contact')
                ->withErrors(['email' => 'Email sudah pernah digunakan untuk kontak. Silakan gunakan email lain atau tunggu balasan admin.'])
                ->withInput();
        }

        Contact::create($validated);

        Mail::to('redeemself0@gmail.com')->send(new ContactMail($validated));

        return redirect()->route('contact')->with('success', 'Pesan Anda telah terkirim. Terima kasih!');
    }

    public function products(Request $request): View
    {
        // Perbaikan: Kirim data kategori ke view
        $categories = Category::where('status', 1)->orderBy('name')->get();

        $query = Product::with('category')->where('status', 1);

        // Filter kategori
        $category = $request->input('category');
        if ($category) {
            $query->where('category_id', $category);
        }
        // Filter pencarian produk
        $search = $request->input('search');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // PERBAIKAN: Gunakan paginate() daripada get() untuk menampilkan produk
        // Ini akan menyediakan method total(), links(), dll yang diperlukan view
        $products = $query->paginate(12)->withQueryString();

        $banners    = Banner::where('status', 1)->get();
        $promotions = Promotion::active()->get();
        $faqs       = Faq::where('status', 1)->orderBy('order')->get();
        $feedbacks  = Feedback::where('status', 'APPROVED')->latest()->take(10)->get();

        return view('products.index', [
            'products'   => $products,
            'categories' => $categories,
            'banners'    => $banners,
            'promotions' => $promotions,
            'faqs'       => $faqs,
            'feedbacks'  => $feedbacks,
        ]);
    }

    public function showProduct(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function productsByCategory($category): View
    {
        $products = Product::whereHas('category', function ($q) use ($category) {
            $q->where('name', $category);
        })->get();

        return view('products.category', compact('products', 'category'));
    }

    public function services(): View
    {
        $services = [
            (object)[
                'icon' => '🌳',
                'title' => 'Jasa Bikin Taman',
                'short_description' => 'Pembuatan taman profesional',
            ],
            (object)[
                'icon' => '🪴',
                'title' => 'Jasa Landscape',
                'short_description' => 'Penataan landscape rumah',
            ],
            (object)[
                'icon' => '🐠',
                'title' => 'Jasa Kolam Ikan',
                'short_description' => 'Desain kolam ikan minimalis',
            ],
            (object)[
                'icon' => '🏡',
                'title' => 'Renovasi Taman',
                'short_description' => 'Renovasi dan perbaikan taman',
            ],
            (object)[
                'icon' => '📐',
                'title' => 'Konsultasi Desain',
                'short_description' => 'Konsultasi desain taman',
            ],
        ];

        return view('services.index', compact('services'));
    }

    public function sitemapXml()
    {
        return response()
            ->view('sitemap.xml')
            ->header('Content-Type', 'application/xml');
    }

    public function sitemapHtml(): View
    {
        return view('sitemap');
    }
}
