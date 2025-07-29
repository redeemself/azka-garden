<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // Pastikan setiap artikel memiliki field 'excerpt', ambil dari 'summary' jika ada
        $rawArticles = [
            [
                'title' => 'Jamani Dolar (ZZ Plant / Zamioculcas)',
                'url' => 'https://www.haibunda.com/moms-life/20220805114902-76-280780/5-tips-merawat-tanaman-hias-dolar-atau-zz-plant-bagus-diletakkan-di-dalam-ruangan',
                'desc' => '5 Tips Merawat ZZ Plant, media dan penempatan.',
                'summary' => 'Panduan perawatan ZZ Plant mulai dari penempatan, penyiraman, hingga media tanam dan pencahayaan terbaik agar tumbuh subur dan tetap sehat.'
            ],
            [
                'title' => 'Dragon Sekel/Tengkorak (Caladium Dragon Scale / Dragon Bone)',
                'url' => 'https://artikel.rumah123.com/jenis-tanaman-keladi-paling-diminati-dan-harganya-75996',
                'desc' => 'Keladi Dragon Scale, perawatan dan harga.',
                'summary' => 'Ulasan tentang jenis Keladi Dragon Scale, ciri, tren harga, serta tips perawatan agar warna dan bentuk daun tetap menarik.'
            ],
            [
                'title' => 'Pakis Kuning',
                'url' => 'https://gdm.id/cara-menanam-pakis/',
                'desc' => 'Cara menanam dan merawat pakis, termasuk pakis kuning dan varian pakis lainnya.',
                'summary' => 'Panduan lengkap budidaya pakis: pemilihan media, teknik tanam, kebutuhan cahaya dan kelembapan untuk menghasilkan pakis kuning yang sehat dan subur.'
            ],
            [
                'title' => 'Kuping Gajah (Anthurium crystallinum)',
                'url' => 'https://www.ladiestory.id/5-cara-merawat-tanaman-kuping-gajah-agar-tumbuh-cantik-dan-subur-56464',
                'desc' => 'Tips dan media tanam Kuping Gajah.',
                'summary' => 'Tips memilih media tanam, penyiraman, dan pemupukan agar Anthurium Kuping Gajah tumbuh besar, daunnya lebar, dan bercorak indah.'
            ],
            [
                'title' => 'Cemara Ekor Tupai (Asparagus densiflorus \'Myers\')',
                'url' => 'https://www.99.co/id/panduan/cemara-ekor-tupai/',
                'desc' => 'Deskripsi, cara tanam, manfaat cemara ekor tupai.',
                'summary' => 'Mengenal karakter tanaman Cemara Ekor Tupai, manfaat, serta teknik tanam dan perawatan agar tanaman subur sebagai penghias taman.'
            ],
            [
                'title' => 'Pot Tanah Coklat, Hitam, Putih diameter 15',
                'url' => 'https://www.99.co/id/panduan/tips-memilih-pot-tanaman-hias/',
                'desc' => 'Panduan memilih pot tanah liat, plastik, keramik sesuai ukuran tanaman.',
                'summary' => 'Tips memilih pot tanah liat, plastik, keramik, dan cara menyesuaikan ukuran pot dengan kebutuhan tanaman hias agar tumbuh optimal.'
            ],
            [
                'title' => 'Puting Cabe (Capsicum ornamental/Chili Ornamental)',
                'url' => 'https://www.faunadanflora.com/tanaman-hias-cabe/',
                'desc' => 'Jenis-jenis cabe hias dan tips perawatan.',
                'summary' => 'Mengenal berbagai jenis cabe hias, keindahan warna buahnya, serta cara merawat agar tanaman tetap berbuah lebat dan sehat.'
            ],
            [
                'title' => 'Cemara Perak',
                'url' => 'https://www.99.co/id/panduan/tanaman-cemara-perak/',
                'desc' => 'Manfaat dan cara tanam cemara perak.',
                'summary' => 'Ulasan manfaat Cemara Perak sebagai pagar hidup dan peneduh, serta cara penanaman dan perawatan agar tumbuh rimbun.'
            ],
            [
                'title' => 'Bringin Korea Tinggi 2M (Ficus microcarpa Korea)',
                'url' => 'https://gdm.id/cara-merawat-bonsai-beringin/',
                'desc' => 'Tips merawat bonsai beringin, cocok untuk varietas Korea.',
                'summary' => 'Langkah merawat bonsai beringin Korea, mulai dari pemangkasan, pemupukan, hingga pemilihan media tanam agar bentuk tetap indah.'
            ],
            [
                'title' => 'Gestrum Kuning (Cestrum aurantiacum)',
                'url' => 'https://tanaman-hias.com/mengenal-gestrum-tanaman-hias-berbunga-wangi/',
                'desc' => 'Mengenal gestrum, manfaat, dan cara tanam.',
                'summary' => 'Penjelasan tentang karakter tanaman Gestrum Kuning, manfaat sebagai tanaman hias berbunga wangi, serta cara tanam dan perawatannya.'
            ],
            [
                'title' => 'Brokoli Hijau (Brassica oleracea, Ornamental Broccoli)',
                'url' => 'https://www.kompas.com/food/read/2023/07/21/110000775/cara-menanam-brokoli-di-rumah',
                'desc' => 'Cara menanam brokoli untuk hias dan konsumsi.',
                'summary' => 'Panduan menanam brokoli hijau di rumah untuk konsumsi dan hias, dari pemilihan benih hingga perawatan harian.'
            ],
            [
                'title' => 'Siklok (Agave angustifolia)',
                'url' => 'https://www.99.co/id/panduan/tanaman-siklok-tahan-panas/',
                'desc' => 'Keunggulan siklok sebagai tanaman hias tahan panas.',
                'summary' => 'Mengenal keunggulan Siklok sebagai tanaman hias yang tahan panas, serta tips perawatan agar tetap hijau dan tumbuh sehat.'
            ],
            [
                'title' => 'Sampang Dara (Alternanthera ficoidea)',
                'url' => 'https://www.kompas.com/homey/read/2020/09/23/200000376/mengenal-tanaman-hias-sampang-dara-dan-cara-merawatnya',
                'desc' => 'Profil dan cara merawat sampang dara.',
                'summary' => 'Profil Alternanthera Sampang Dara, ciri daun, serta cara merawat agar warna daun tetap cerah dan lebat.'
            ],
            [
                'title' => 'Kana (Canna indica)',
                'url' => 'https://gdm.id/cara-menanam-kana/',
                'desc' => 'Panduan menanam dan merawat bunga kana.',
                'summary' => 'Panduan budidaya bunga kana, teknik menanam, pemupukan, serta pencegahan hama agar berbunga cantik.'
            ],
            [
                'title' => 'Teratai (Nymphaea)',
                'url' => 'https://www.99.co/id/panduan/tanaman-teratai-dan-cara-merawatnya/',
                'desc' => 'Panduan lengkap menanam teratai dalam kolam dan pot air.',
                'summary' => 'Langkah-langkah menanam teratai di kolam/taman air: penempatan, media tanam, pemupukan, dan pencahayaan.'
            ],
            [
                'title' => 'Airis Brazil (Neomarica gracilis, Brazilian iris)',
                'url' => 'https://www.tokotanaman.com/airis-brazil/',
                'desc' => 'Deskripsi, manfaat, cara tanam airis brazil.',
                'summary' => 'Deskripsi Airis Brazil, keindahan bunga, manfaat, dan teknik perawatan agar tumbuh subur.'
            ],
            [
                'title' => 'Batu Taman (Batu Hias Hitam, Putih)',
                'url' => 'https://www.casaindonesia.com/article/read/4/2020/1902/Tips-Pilih-Batu-Taman-dan-Perawatannya',
                'desc' => 'Tips memilih dan merawat batu taman sebagai elemen dekoratif.',
                'summary' => 'Tips memilih jenis batu taman hias, penempatan, serta cara merawat agar tetap bersih dan awet.'
            ],
            [
                'title' => 'Maranti Bali (Maranta leuconeura/Prayer Plant)',
                'url' => 'https://www.ruparupa.com/blog/cara-merawat-tanaman-maranta/',
                'desc' => 'Maranta, perawatan dan kegunaan.',
                'summary' => 'Cara merawat Maranta Bali agar daunnya tetap bercorak dan tumbuh subur, serta manfaat sebagai tanaman indoor.'
            ],
            [
                'title' => 'Kadaka Tanduk (Platycerium bifurcatum)',
                'url' => 'https://www.99.co/id/panduan/tanaman-kadaka-tanduk/',
                'desc' => 'Karakter, perawatan dan cara tempel kadaka tanduk.',
                'summary' => 'Teknik merawat Kadaka Tanduk, cara penempelan di papan/tembok, serta kebutuhan cahaya dan air.'
            ],
            [
                'title' => 'Jayen (Mimusops elengi / Keben)',
                'url' => 'https://www.kompasiana.com/iwansuwitomo/5e833a0c097f364c4d43a832/keben-si-peneduh-sejuta-manfaat',
                'desc' => 'Profil pohon keben/jayen sebagai peneduh, tanaman konservasi.',
                'summary' => 'Profil pohon Jayen/Keben: ciri, manfaat sebagai peneduh, dan nilai konservasi tanaman asli Indonesia.'
            ],
            [
                'title' => 'Alamanda Kuning (Allamanda cathartica)',
                'url' => 'https://gdm.id/tanaman-alamanda/',
                'desc' => 'Jenis, manfaat, dan perawatan alamanda kuning.',
                'summary' => 'Jenis-jenis Alamanda Kuning, manfaat penghias taman, dan cara merawat agar selalu berbunga.'
            ],
            [
                'title' => 'Sarbena Putih/Hijau (Alternanthera, Iresine herbstii)',
                'url' => 'https://www.faunadanflora.com/tanaman-hias-daun-sarbena/',
                'desc' => 'Mengenal sarbena, tips merawat sarbena daun putih/hijau.',
                'summary' => 'Mengenal Sarbena Putih/Hijau, karakter daun, serta tips pemupukan dan penyiraman.'
            ],
            [
                'title' => 'Pitalub Kecil/Tinggi (Portulaca grandiflora, Moss Rose)',
                'url' => 'https://www.faunadanflora.com/tanaman-hias-purslane/',
                'desc' => 'Mengenal pitalub, atau portulaca, perawatan dan manfaatnya.',
                'summary' => 'Penjelasan Portulaca (Pitalub), manfaat sebagai tanaman berbunga di taman, dan cara perawatan agar berbunga lebat.'
            ],
            [
                'title' => 'Aglonema Valentin',
                'url' => 'https://www.99.co/id/panduan/aglaonema-valentine/',
                'desc' => 'Profil, ciri dan cara menanam aglonema valentin.',
                'summary' => 'Karakter Aglonema Valentin, pola warna daun, teknik tanam, dan pemupukan agar tumbuh maksimal.'
            ],
            [
                'title' => 'Pot Kapsul Coklat/Hitam diameter 35/60cm',
                'url' => 'https://www.kompas.com/homey/read/2021/08/19/070000176/tips-memilih-pot-untuk-tanaman-hias',
                'desc' => 'Tips memilih pot besar/kapsul untuk tanaman hias tinggi.',
                'summary' => 'Panduan memilih pot kapsul besar untuk tanaman hias tinggi, termasuk material dan tata letak.'
            ],
            [
                'title' => 'Pot Tanah Coklat, Putih, Bintik Hitam Diameter 30',
                'url' => 'https://www.rumah.com/berita-properti/5-tips-memilih-pot-tanaman-hias-yang-tepat-12348',
                'desc' => 'Tips memilih pot tanah liat dan variasi warna/bentuk.',
                'summary' => 'Tips memilih pot tanah liat, variasi warna/bentuk, dan fungsinya untuk tanaman hias.'
            ],
            [
                'title' => 'Pot Hitam Diameter 40',
                'url' => 'https://www.cnnindonesia.com/gaya-hidup/20210922113526-277-697288/tips-memilih-pot-untuk-tanaman-hias',
                'desc' => 'Memilih pot plastik hitam untuk tanaman besar/tinggi.',
                'summary' => 'Cara memilih pot plastik hitam diameter besar untuk tanaman tinggi, agar akar berkembang optimal.'
            ],
            [
                'title' => 'Cemara Tretes, Tinggi 120cm (Juniperus/Thuja)',
                'url' => 'https://www.99.co/id/panduan/tanaman-cemara-tretes/',
                'desc' => 'Panduan memilih, menanam dan manfaat cemara tretes.',
                'summary' => 'Ulasan Cemara Tretes sebagai tanaman pagar dan hias, serta panduan menanam dan pemeliharaan.'
            ],
            [
                'title' => 'Ketapang Kaligata Tinggi 60cm',
                'url' => 'https://www.kompas.com/homey/read/2022/10/25/070000276/mengenal-tanaman-ketapang-kencana-dan-cara-perawatannya',
                'desc' => 'Profil dan perawatan ketapang, termasuk varietas kaligata.',
                'summary' => 'Profil Ketapang Kaligata, bentuk daun, fungsi sebagai peneduh, dan cara menanamnya.'
            ],
            [
                'title' => 'Berekele (Bryophyllum pinnatum, Cocor Bebek)',
                'url' => 'https://www.kompas.com/homey/read/2021/10/22/200000376/mengenal-tanaman-cocor-bebek-dan-manfaatnya',
                'desc' => 'Mengenal cocor bebek/berekele, manfaat dan perawatan.',
                'summary' => 'Karakter tanaman Cocor Bebek, manfaat kesehatan, serta cara perawatan agar tumbuh subur.'
            ],
            [
                'title' => 'Media Tanah (Media Tanam Siap Pakai)',
                'url' => 'https://www.99.co/id/panduan/media-tanam-tanaman-hias/',
                'desc' => 'Jenis media tanam dan campuran terbaik untuk tanaman hias.',
                'summary' => 'Ulasan berbagai jenis media tanam, campuran tanah, sekam, kompos, dan tips agar tanaman tumbuh sehat.'
            ],
            [
                'title' => 'Jamani Cobra',
                'url' => 'https://indonesian.alibaba.com/product-detail/jamani-cobra-tanaman-hias-1600189364088.html',
                'desc' => 'Deskripsi singkat, info marketplace Jamani Cobra.',
                'summary' => 'Informasi Jamani Cobra, jenis, karakter daun, dan sumber pembelian online.'
            ],
            [
                'title' => 'Kamboja Jepang (Adenium obesum)',
                'url' => 'https://www.99.co/id/panduan/tanaman-adenium/',
                'desc' => 'Panduan perawatan kamboja jepang, adenium.',
                'summary' => 'Panduan menanam dan merawat Adenium, karakter bunga, dan pemupukan agar selalu berbunga.'
            ],
            [
                'title' => 'Bringin Putih (Ficus benjamina variegata)',
                'url' => 'https://gdm.id/cara-merawat-bonsai-beringin/',
                'desc' => 'Merawat beringin/ficus putih, cocok untuk bonsai dan hias taman.',
                'summary' => 'Cara merawat beringin putih variegata untuk bonsai dan taman, agar bentuk daun tetap indah.'
            ],
            [
                'title' => 'Bromolian Baby Pink (Bromeliad)',
                'url' => 'https://www.rumah.com/berita-properti/mengenal-bromelia-tanaman-hias-unik-dan-eksotis-20079',
                'desc' => 'Profil dan perawatan bromeliad/bromolia.',
                'summary' => 'Profil Bromeliad Baby Pink, keindahan warna dan bentuk, serta tips perawatan agar tetap segar.'
            ],
            [
                'title' => 'Asoka India (Ixora chinensis)',
                'url' => 'https://www.kompas.com/homey/read/2020/11/13/080000076/cara-menanam-bunga-asoka-dan-perawatannya',
                'desc' => 'Cara menanam dan merawat asoka india.',
                'summary' => 'Teknik menanam dan merawat bunga Asoka India agar selalu berbunga cerah.'
            ],
            [
                'title' => 'Pandan Bali (Pandanus amaryllifolius/odorifer)',
                'url' => 'https://www.kompas.com/homey/read/2021/07/10/200000276/mengenal-tanaman-pandan-bali-dan-manfaatnya',
                'desc' => 'Manfaat dan kegunaan pandan bali sebagai penghias taman.',
                'summary' => 'Manfaat Pandan Bali sebagai penghias taman dan sumber aroma alami, serta tips menanamnya.'
            ],
            [
                'title' => 'Lidah Mertua (Sansevieria)',
                'url' => 'https://gdm.id/tanaman-lidah-mertua/',
                'desc' => 'Manfaat, jenis dan cara merawat lidah mertua.',
                'summary' => 'Manfaat Lidah Mertua sebagai pembersih udara, teknik perawatan, dan pemilihan varietas.'
            ],
            [
                'title' => 'Bringin Korea Micro',
                'url' => 'https://bonsai.co.id/mengenal-bringin-korea/',
                'desc' => 'Profil bringin korea, jenis micro untuk bonsai mahal.',
                'summary' => 'Profil Bonsai Bringin Korea Micro, ciri-ciri daun, dan cara perawatan agar tetap mini.'
            ],
            [
                'title' => 'Marigool (Marigold, Tagetes erecta)',
                'url' => 'https://www.99.co/id/panduan/tanaman-marigold/',
                'desc' => 'Panduan menanam dan merawat bunga marigold.',
                'summary' => 'Tips menanam Marigold, pemilihan benih, dan perawatan agar berbunga kuning cerah.'
            ],
            [
                'title' => 'Kaktus Koboy, Tinggi 70 cm (Cereus peruvianus/cactus cowboy)',
                'url' => 'https://www.99.co/id/panduan/tanaman-kaktus-cowboy/',
                'desc' => 'Panduan memilih dan merawat kaktus koboy.',
                'summary' => 'Ulasan jenis Kaktus Koboy, teknik penanaman, serta tips agar tumbuh tinggi dan sehat.'
            ],
            [
                'title' => 'Bonsai Gestrum Ukuran L/M (Cestrum nocturnum bonsai)',
                'url' => 'https://tanaman-hias.com/mengenal-gestrum-tanaman-hias-berbunga-wangi/',
                'desc' => 'Profil gestrum sebagai tanaman dan bonsai.',
                'summary' => 'Panduan Gestrum sebagai bonsai, karakter bunga malam, dan teknik pemangkasan.'
            ],
            [
                'title' => 'Bonsai Cemara Udang (Casuarina equisetifolia)',
                'url' => 'https://gdm.id/bonsai-cemara-udang/',
                'desc' => 'Tips dan perawatan bonsai cemara udang.',
                'summary' => 'Langkah merawat Bonsai Cemara Udang, pemangkasan, pemupukan, dan media tanam yang sesuai.'
            ],
            [
                'title' => 'Bunga Kertas (Bougenville)',
                'url' => 'https://www.99.co/id/panduan/tanaman-bougenville/',
                'desc' => 'Cara menanam dan merawat bunga kertas.',
                'summary' => 'Panduan menanam Bougenville, pemangkasan, dan teknik agar warna bunga tetap cerah.'
            ],
            [
                'title' => 'Jambu Kanci (Guava dwarf)',
                'url' => 'https://www.bibitonline.com/tanaman/jambu-kanci',
                'desc' => 'Profil jambu kanci, deskripsi varietas mini untuk hias atau konsumsi.',
                'summary' => 'Profil Jambu Kanci, manfaat sebagai tanaman hias dan konsumsi, serta teknik tanam di pot.'
            ],
            [
                'title' => 'Jeruk Lemon',
                'url' => 'https://www.kompas.com/food/read/2022/07/11/120000876/cara-menanam-jeruk-lemon-di-pot-agar-cepat-berbuah',
                'desc' => 'Cara menanam jeruk lemon di pot.',
                'summary' => 'Langkah-langkah menanam Jeruk Lemon di pot agar cepat berbuah dan sehat.'
            ],
            [
                'title' => 'Asoka Singapur (Ixora javanica)',
                'url' => 'https://www.bukalapak.com/blog/lifestyle/7-tips-merawat-tanaman-hias-bunga-asoka-15133',
                'desc' => 'Tips perawatan asoka singapur.',
                'summary' => 'Tips merawat Asoka Singapur agar bunga tetap lebat dan tahan lama.'
            ],
            [
                'title' => 'Sikas, Tinggi 70 cm (Cycas revoluta)',
                'url' => 'https://www.99.co/id/panduan/tanaman-sikas/',
                'desc' => 'Panduan merawat dan mengenal tanaman sikas.',
                'summary' => 'Panduan merawat Sikas agar tumbuh tinggi, sehat, dan berdaun lebat.'
            ],
            [
                'title' => 'Kadaka Tempel (Platycerium sp. / staghorn fern tempel)',
                'url' => 'https://www.kompas.com/homey/read/2022/07/22/070000676/cara-merawat-pakis-tanduk-di-dinding-agar-tumbuh-subur',
                'desc' => 'Cara merawat pakis tanduk/kadaka tempel di tembok/papan.',
                'summary' => 'Teknik menempel Kadaka di papan/tembok, pemupukan, dan penyiraman yang tepat.'
            ],
            [
                'title' => 'Pucuk Merah, Tinggi 250 cm (Syzygium oleina)',
                'url' => 'https://www.99.co/id/panduan/tanaman-pucuk-merah/',
                'desc' => 'Profil lengkap dan perawatan pucuk merah sebagai pagar atau pohon hias.',
                'summary' => 'Profil Pucuk Merah, manfaat sebagai pagar hidup, dan teknik perawatan agar tumbuh cepat.'
            ],

            // Referensi Katalog Azka Garden
            [
                'title' => 'Kanal YouTube Azka Garden Indonesia',
                'url' => 'https://www.youtube.com/channel/UCuAUD9jzepl1iay_eIlDgKw',
                'desc' => 'Video koleksi, update stok, dan tips Azka Garden.',
                'summary' => 'Channel YouTube Azka Garden berisi video koleksi, update mingguan, dan tips budidaya tanaman langsung dari lapak.'
            ],
            [
                'title' => 'Instagram @azka_garden',
                'url' => 'https://www.instagram.com/azka_garden/',
                'desc' => 'Katalog, promosi, dan update stok Azka Garden.',
                'summary' => 'Akun Instagram Azka Garden menampilkan katalog produk, promosi, dan update stok terbaru setiap pekan.'
            ],
            [
                'title' => 'Profil Toko Bunga Hendrik / Azka Garden',
                'url' => 'https://www.semuabis.com/toko-bunga-hendrik-0896-3508-6182?utm_source=chatgpt.com',
                'desc' => 'Profil bisnis, alamat, dan katalog Azka Garden.',
                'summary' => 'Profil bisnis Toko Bunga Hendrik/Azka Garden di semuabis.com: info alamat, produk, dan kontak.'
            ],
            [
                'title' => 'Google Maps Toko Bunga Hendrik',
                'url' => 'https://maps.app.goo.gl/j5AuLF1AZ3VVgovcA',
                'desc' => 'Lokasi, review pelanggan, dan dokumentasi Azka Garden.',
                'summary' => 'Google Maps Azka Garden: lokasi fisik, review pelanggan, dan dokumentasi spanduk/kios.'
            ],
        ];

        // Set excerpt dari summary jika excerpt tidak disediakan
        $articles = [];
        foreach ($rawArticles as $a) {
            $a['excerpt'] = $a['desc'] ?? ($a['summary'] ?? 'Ringkasan belum tersedia.');
            $articles[] = $a;
        }

        // Filter pencarian
        $search = $request->input('search');
        if ($search) {
            $search = strtolower($search);
            $articles = array_filter($articles, function ($article) use ($search) {
                return strpos(strtolower($article['title']), $search) !== false
                    || strpos(strtolower($article['excerpt']), $search) !== false;
            });
        }

        return view('artikel.index', [
            'articles' => $articles,
            'search'   => $search,
        ]);
    }
}
