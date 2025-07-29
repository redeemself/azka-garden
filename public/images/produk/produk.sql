-- =====================================================
-- Bersihkan Data Lama
-- =====================================================
DELETE FROM product_images;
DELETE FROM products;
DELETE FROM categories;
DELETE FROM banners;
DELETE FROM promotions;

-- =====================================================
-- Insert Kategori
-- =====================================================
INSERT INTO categories (id, name, description, status, interface_id) VALUES
(1, 'Tanaman Hias', 'Tanaman hias indoor dan outdoor', 1, 1),
(2, 'Pot', 'Berbagai jenis pot taman', 1, 1),
(3, 'Batu Hias', 'Batu taman hias', 1, 1),
(4, 'Tanah', 'Media tanah kemasan', 1, 1);

-- =====================================================
-- Insert Produk Lengkap dengan Deskripsi dan Path Gambar (.jpg utama)
-- =====================================================
INSERT INTO products (id, category_id, name, description, stock, price, weight, image_url, status, interface_id, is_featured) VALUES
(54, 1, 'Jamani Dolar', 'Jamani Dolar (Zamioculcas zamiifolia) merupakan tanaman perennial tropis dari keluarga Araceae yang berasal dari Afrika Timur seperti Kenya, Tanzania, dan Afrika Selatan. Tumbuh dari rimpang tebal yang menyimpan cadangan air, tanaman ini menghasilkan daun majemuk menyirip berwarna hijau pekat dan mengkilap dengan 6–8 pasang foliol oval sepanjang 7–15 cm. ZZ Plant sangat toleran terhadap cahaya rendah hingga sedang dan mampu bertahan lama dalam kondisi kekeringan. Harga pasaran tanaman ini sekitar Rp70.000.', 10, 70000.00, 1.00, 'images/produk/jamani_dolar.jpg', 1, 1, 0),
(55, 1, 'Dragon Sekel', 'Dragon Sekel atau Tengkorak (Alocasia baginda ''Dragon Scale'') adalah varietas Alocasia dari keluarga Araceae yang terkenal dengan motif daun unik menyerupai sisik naga. Daunnya hijau zamrud dengan urat perak metalik yang mencolok, memberikan kesan eksotis dan elegan. Tanaman ini sangat cocok untuk dekorasi interior karena toleransinya terhadap cahaya rendah hingga sedang serta kemampuannya bertahan dalam kondisi kering. Harga pasaran sekitar Rp125.000.', 8, 125000.00, 1.00, 'images/produk/dragon_sekel_atau_tengkorak.jpg', 1, 1, 0),
(56, 1, 'Pakis Kuning', 'Pakis Kuning (Nephrolepis exaltata ''Golden'') adalah varietas pakis hias yang memiliki daun muda berwarna kuning cerah yang berubah menjadi hijau saat dewasa. Daunnya berbentuk pedang dan tumbuh merumpun, menciptakan tampilan alami dan menyegarkan. Tanaman ini ideal ditempatkan di area teduh dengan cahaya matahari tidak langsung dan mudah dirawat, memberikan sentuhan hijau segar pada lingkungan sekitar. Harga pasaran sekitar Rp25.000.', 20, 25000.00, 1.00, 'images/produk/pakis_kuning.jpg', 1, 1, 0),
(57, 1, 'Kuping Gajah', 'Kuping Gajah (Anthurium crystallinum) adalah varietas Anthurium dari keluarga Araceae dengan daun besar berbentuk hati dan permukaan berkilau. Urat daun berwarna keputih-putihan yang mencolok menambah kesan elegan dan eksotis. Tanaman ini cocok untuk dekorasi interior, memiliki toleransi terhadap cahaya rendah hingga sedang serta tahan pada periode kekeringan. Harga pasaran sekitar Rp75.000.', 15, 75000.00, 1.00, 'images/produk/kuping_gajah.jpg', 1, 1, 0),
(58, 1, 'Cemara Ekor Tupai', 'Cemara Ekor Tupai (Asparagus densiflorus) merupakan tanaman tahunan hijau abadi dari keluarga Asparagaceae yang berasal dari Afrika Selatan. Daunnya menyerupai ekor tupai dengan daun kecil berwarna hijau cerah yang tumbuh rimbun dan mengerucut. Tanaman ini cocok sebagai tanaman hias interior karena toleransi terhadap cahaya rendah hingga sedang serta kemampuannya bertahan pada kondisi kering. Harga pasaran sekitar Rp40.000.', 12, 40000.00, 1.00, 'images/produk/cemara_ekor_tupay.jpg', 1, 1, 0),
(59, 2, 'Pot Tanah Liat', 'Pot Tanah Liat diameter 15 cm dibuat dari bahan tanah liat berkualitas tinggi dengan desain minimalis yang sesuai untuk berbagai tanaman hias kecil hingga sedang. Pot ini tersedia dalam warna coklat, hitam, dan putih, memberikan pilihan dekorasi menarik serta harga terjangkau untuk menambah estetika tanaman di rumah Anda. Harga pot ini sekitar Rp40.000.', 50, 40000.00, 2.00, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.jpg', 1, 1, 0),
(60, 1, 'Puting Cabe', 'Puting Cabe (Euphorbia milii) adalah tanaman hias berbunga dari keluarga Euphorbiaceae yang memiliki bunga kecil cerah serta duri tajam pada batangnya. Daunnya hijau rapat dengan bunga muncul dalam kelompok kecil, menciptakan tampilan eksotis. Tanaman ini tahan terhadap cahaya rendah hingga sedang dan mampu bertahan dalam kondisi kering berkat cadangan air pada batangnya. Harga pasaran sekitar Rp10.000.', 30, 10000.00, 0.30, 'images/produk/puting_cabe.jpg', 1, 1, 0),
(61, 1, 'Cemara Perak', 'Cemara Perak (Juniperus chinensis) merupakan tanaman konifer hijau kekuningan berbentuk rimbun menyerupai pohon cemara mini. Tanaman ini cocok untuk taman, halaman, maupun sebagai tanaman indoor, memberikan kesan alami dan segar. Harga pasaran sekitar Rp50.000.', 10, 50000.00, 2.00, 'images/produk/cemara_perak.jpg', 1, 1, 0),
(62, 1, 'Bringin Korea Tinggi 2M', 'Bringin Korea (Ficus microcarpa) adalah tanaman hias populer untuk taman dan interior dengan tinggi sekitar 2 meter, batang kokoh, dan daun hijau mengkilap yang memberikan suasana alami dan sejuk. Harga pasaran sekitar Rp2.000.000, mencerminkan kualitas dan ukuran yang besar. Tanaman ini mudah beradaptasi dengan berbagai kondisi cahaya dan perawatan sehingga cocok untuk pemula maupun penghobi.', 2, 2000000.00, 8.00, 'images/produk/bringin_korea_tinggi_2M.jpg', 1, 1, 0),
(63, 1, 'Gestrum Kuning', 'Gestrum Kuning (Gestrum coromandelianum) adalah tanaman tropis dengan bunga kuning cerah dan daun hijau lebat yang dapat tumbuh hingga 2 meter. Tanaman ini cocok untuk taman atau halaman rumah, tahan berbagai kondisi cuaca dan mudah dirawat. Harga pasar sekitar Rp30.000.', 15, 30000.00, 1.00, 'images/produk/gestrum_kuning.jpg', 1, 1, 0),
(64, 1, 'Brokoli Hijau', 'Brokoli Hijau adalah tanaman hias dengan daun hijau segar yang menyerupai sayur brokoli. Tanaman ini sering digunakan sebagai tanaman hias unik yang menambah sentuhan alami pada taman atau ruangan. Harga pasaran sekitar Rp10.000.', 25, 10000.00, 0.30, 'images/produk/brokoli_hijau.jpg', 1, 1, 0),
(65, 1, 'Siklok', 'Siklok (Agave attenuata) atau Foxtail Agave adalah tanaman sukulen asal Meksiko dengan daun panjang runcing berwarna hijau keabu-abuan dengan pinggiran putih membentuk roseta yang elegan. Tahan terhadap panas dan kekeringan, cocok untuk taman tropis maupun subtropis serta perawatan mudah. Harga sekitar Rp70.000.', 10, 70000.00, 2.00, 'images/produk/siklok.jpg', 1, 1, 0),
(66, 1, 'Sampang Dara', 'Sampang Dara (Excoecaria cochinchinensis) adalah perdu tropis dengan daun hijau cerah di bagian atas dan merah gelap di bagian bawah, tumbuh hingga 1–2 meter. Memberikan tampilan alami dan eksotis, tanaman ini cocok untuk taman indoor maupun outdoor. Harga sekitar Rp16.000, namun perlu hati-hati karena getahnya beracun saat perawatan.', 15, 16000.00, 1.00, 'images/produk/sampang_dara.jpg', 1, 1, 0),
(68, 1, 'Teratai', 'Teratai (Nymphaea) adalah tanaman air dengan bunga besar indah yang mengapung di permukaan air. Warnanya bervariasi dari putih, merah muda hingga ungu, sering digunakan untuk mempercantik kolam atau taman air. Harga pasaran sekitar Rp75.000.', 10, 75000.00, 2.00, 'images/produk/teratai.jpg', 1, 1, 0),
(69, 1, 'Airis Brazil', 'Airis Brazil (Iris variegata) adalah tanaman hias outdoor dengan daun panjang hijau cerah bergaris putih yang memberikan tampilan segar dan menarik, cocok untuk taman dan pot. Harga pasar sekitar Rp10.000.', 10, 10000.00, 0.30, 'images/produk/airis_brazil.jpg', 1, 1, 0),
(70, 3, 'Batu Taman Hitam Putih', 'Batu Taman Hitam dan Putih adalah batu hias yang digunakan untuk dekorasi taman, tersedia dalam warna hitam dan putih yang memberikan kontras alami dan estetis pada taman. Harga sekitar Rp30.000.', 100, 30000.00, 2.00, 'images/produk/batu_taman_hitam_putih.jpg', 1, 1, 0),
(71, 1, 'Maranti Bali', 'Maranti Bali (Stromanthe sanguinea) adalah tanaman hias tropis dari hutan hujan Brasil yang memiliki daun berwarna-warni merah, hijau, dan putih mencolok, sangat populer di kalangan penggemar tanaman hias. Harga pasaran sekitar Rp15.000.', 15, 15000.00, 0.70, 'images/produk/maranti_bali.jpg', 1, 1, 0),
(72, 1, 'Kadaka Tanduk', 'Kadaka Tanduk (Platycerium) adalah tanaman paku-pakuan epifit yang biasanya hidup menempel pada batang tanaman lain, namun dapat juga ditanam dalam pot dan umum ditemukan di daerah lembap. Harga sekitar Rp30.000.', 10, 30000.00, 0.50, 'images/produk/kadaka_tanduk.jpg', 1, 1, 0),
(73, 1, 'Jayen', 'Jayen (Episcia) adalah tanaman hias indoor dengan daun berbentuk hati dan bunga kecil berwarna cerah, cocok untuk dekorasi meja atau rak tanaman dalam ruangan. Harga sekitar Rp80.000.', 5, 80000.00, 0.20, 'images/produk/jayen.jpg', 1, 1, 0),
(74, 1, 'Alamanda Kuning', 'Alamanda Kuning (Allamanda cathartica) adalah tanaman hias berbunga terompet emas berwarna kuning cerah dengan diameter 5–7,5 cm, populer untuk taman dan pagar hidup. Harga pasar sekitar Rp75.000.', 10, 75000.00, 1.00, 'images/produk/alamanda_kuning.jpg', 1, 1, 0),
(75, 1, 'Sarbena Putih', 'Sarbena Putih (Sabrina) adalah tanaman hias gantung dengan bunga putih kecil yang menawan, ideal untuk taman minimalis atau teras rumah. Harga sekitar Rp10.000.', 20, 10000.00, 0.30, 'images/produk/sarbena_putih.jpg', 1, 1, 0),
(76, 1, 'Sarbena Hijau', 'Sarbena Hijau adalah varian tanaman hias dengan daun hijau cerah yang memberikan kesan segar dan alami pada ruang hijau. Harga sekitar Rp10.000.', 20, 10000.00, 0.30, 'images/produk/sarbena_hijau.jpg', 1, 1, 0),
(77, 1, 'Pitalub Kecil', 'Pitalub Kecil adalah tanaman hias kecil dengan daun lebat berwarna hijau, cocok sebagai penghias meja atau sudut ruangan, mudah dirawat dan sesuai untuk pemula. Harga pasaran sekitar Rp30.000.', 20, 30000.00, 0.30, 'images/produk/pitalub_kecil.jpg', 1, 1, 0),
(78, 1, 'Aglonema Valentin', 'Aglonema Valentin adalah tanaman hias dengan daun hijau-merah muda yang populer untuk dekorasi interior dan mudah tumbuh subur di tempat teduh. Harga sekitar Rp70.000.', 10, 70000.00, 0.40, 'images/produk/aglonema_valentin.jpg', 1, 1, 0),
(79, 2, 'Pot Kapsul', 'Pot Kapsul Coklat dan Hitam (diameter 35 cm, tinggi 60 cm) adalah pot dengan desain kapsul elegan yang cocok untuk tanaman besar atau bonsai. Harga sekitar Rp85.000.', 10, 85000.00, 3.00, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.jpg', 1, 1, 0),
(80, 2, 'Pot Tanah Minimalis', 'Pot Tanah Coklat, Putih, dan Bintik Hitam (diameter 30 cm) adalah pot tanah liat minimalis yang sesuai untuk berbagai tanaman hias. Harga sekitar Rp65.000.', 15, 65000.00, 2.50, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.jpg', 1, 1, 0),
(81, 2, 'Pot Hitam Besar', 'Pot Hitam Diameter 40 cm adalah pot plastik hitam berukuran besar yang tahan lama dan ideal untuk tanaman hias berukuran sedang hingga besar. Pot ini dapat digunakan di dalam maupun luar ruangan. Harga sekitar Rp40.000.', 30, 40000.00, 2.50, 'images/produk/pot_hitam_diameter_40.jpg', 1, 1, 0),
(82, 1, 'Cemara Tretes', 'Cemara Tretes (tinggi 120 cm) adalah tanaman cemara mini yang memberikan kesan asri dan elegan, sangat cocok untuk taman dan penghias ruang luar. Harga pasaran sekitar Rp250.000.', 3, 250000.00, 5.00, 'images/produk/cemara_tretes_tinggi_120cm.jpg', 1, 1, 0),
(83, 1, 'Pitalub Tinggi', 'Pitalub Tinggi 70 cm adalah tanaman hias berukuran sedang dengan daun lebat, mudah dirawat dan sesuai sebagai penghias taman, khususnya bagi pemula. Harga sekitar Rp80.000.', 5, 80000.00, 0.80, 'images/produk/pitalub_tinggi_70cm.jpg', 1, 1, 0),
(84, 1, 'Ketapang Kaligata', 'Ketapang Kaligata Tinggi 60 cm adalah tanaman hias kecil dengan daun khas yang memberikan kesan asri, sangat sesuai untuk taman minimalis. Harga sekitar Rp35.000.', 10, 35000.00, 0.60, 'images/produk/ketapang_kaligata_tinggi_60cm.jpg', 1, 1, 0),
(85, 1, 'Berekele', 'Berekele adalah tanaman hias yang menambah warna dan tekstur pada taman tropis maupun sebagai tanaman pagar hidup. Harga sekitar Rp15.000.', 30, 15000.00, 0.30, 'images/produk/berekele.jpg', 1, 1, 0),
(86, 4, 'Media Tanah', 'Media Tanah adalah media tanam berkualitas tinggi yang mendukung pertumbuhan berbagai tanaman hias dan dapat digunakan untuk tanaman dalam pot maupun di tanah terbuka. Harga sekitar Rp15.000 per kemasan.', 100, 15000.00, 1.00, 'images/produk/media_tanah.jpg', 1, 1, 0),
(87, 1, 'Jamani Cobra', 'Jamani Cobra adalah tanaman hias eksotis dengan bentuk unik dan harga tinggi, sangat cocok untuk koleksi tanaman langka. Harga pasar sekitar Rp300.000.', 3, 300000.00, 0.60, 'images/produk/jamani_cobra.jpg', 1, 1, 0),
(88, 1, 'Kamboja Jepang', 'Kamboja Jepang adalah tanaman hias berbunga cantik dan harum yang sering digunakan sebagai tanaman pekarangan di daerah tropis. Harga sekitar Rp50.000.', 8, 50000.00, 1.20, 'images/produk/kamboja_jepang.jpg', 1, 1, 0),
(89, 1, 'Bringin Putih', 'Bringin Putih adalah tanaman hias dengan daun putih hijau yang menawan, memberikan kesan elegan untuk taman dan halaman. Harga sekitar Rp50.000.', 6, 50000.00, 1.00, 'images/produk/bringin_putih.jpg', 1, 1, 0),
(90, 1, 'Bromelian Baby Pink', 'Bromelian Baby Pink adalah bromeliad dengan bunga pink kecil yang cantik, menjadi favorit tanaman eksotis untuk dekorasi interior. Harga sekitar Rp125.000.', 5, 125000.00, 0.60, 'images/produk/bromilian_baby_pink.jpg', 1, 1, 0),
(91, 1, 'Asoka India', 'Asoka India adalah tanaman berbunga kecil yang sering digunakan sebagai pagar hidup, mudah dirawat dan sesuai untuk pemula. Harga sekitar Rp10.000.', 30, 10000.00, 0.20, 'images/produk/asoka_india.jpg', 1, 1, 0),
(92, 1, 'Pandan Bali', 'Pandan Bali adalah tanaman pandan beraroma khas yang digunakan sebagai tanaman hias dan bumbu dapur di daerah tropis. Harga sekitar Rp150.000.', 10, 150000.00, 5.00, 'images/produk/pandan_bali.jpg', 1, 1, 0),
(93, 1, 'Lidah Mertua', 'Lidah Mertua adalah tanaman hias indoor dengan daun panjang tajam yang mudah dirawat dan sesuai untuk dekorasi meja atau rak tanaman. Harga sekitar Rp25.000.', 15, 25000.00, 0.50, 'images/produk/lidah_mertua.jpg', 1, 1, 0),
(94, 1, 'Bringin Korea Micro', 'Bringin Korea Micro adalah varian kecil dari Bringin Korea yang cocok untuk koleksi bonsai dengan bentuk daun menarik dan perawatan mudah. Harga pasar sekitar Rp1.500.000.', 2, 1500000.00, 3.00, 'images/produk/bringin_korea_micro.jpg', 1, 1, 0),
(95, 1, 'Marigool', 'Marigool adalah tanaman berbunga oranye cerah yang populer sebagai tanaman hias dan penangkal serangga di taman rumah. Harga sekitar Rp25.000.', 25, 25000.00, 0.20, 'images/produk/marigool.jpg', 1, 1, 0),
(96, 1, 'Kaktus Koboy', 'Kaktus Koboy (tinggi 70 cm) adalah kaktus besar berbentuk unik yang tahan kering dan mudah dirawat, sangat cocok untuk dekorasi rumah. Harga sekitar Rp150.000.', 12, 150000.00, 1.20, 'images/produk/kaktus_koboy_tinggi_70cm.jpg', 1, 1, 0),
(97, 1, 'Bonsai Gestrum L', 'Bonsai Gestrum Ukuran L adalah bonsai besar dengan daun Gestrum yang indah, cocok untuk koleksi eksklusif dengan perawatan khusus. Harga pasar sekitar Rp1.200.000.', 1, 1200000.00, 3.00, 'images/produk/bonsai_gestrum(L).jpg', 1, 1, 0),
(98, 1, 'Bonsai Gestrum M', 'Bonsai Gestrum Ukuran M adalah bonsai berukuran sedang dengan daun Gestrum yang cantik, memberikan kesan elegan di rumah atau kantor. Harga sekitar Rp500.000.', 2, 500000.00, 2.00, 'images/produk/bonsai_gestrum(M).jpg', 1, 1, 0),
(99, 1, 'Bonsai Cemara Udang', 'Bonsai Cemara Udang adalah bonsai cemara unik yang menyerupai udang dan merupakan tanaman koleksi menarik dengan perawatan khusus. Harga pasar sekitar Rp650.000.', 1, 650000.00, 2.00, 'images/produk/bonsai_cemara_udang.jpg', 1, 1, 0),
(100, 1, 'Bunga Kertas', 'Bunga Kertas adalah tanaman hias dengan warna-warni cerah yang mudah dirawat dan cocok untuk memperindah pagar atau taman. Tanaman ini sangat sesuai bagi pemula. Harga sekitar Rp30.000.', 20, 30000.00, 0.40, 'images/produk/bunga_kertas.jpg', 1, 1, 0),
(101, 1, 'Jambu Kanci', 'Jambu Kanci (tinggi 50 cm) adalah tanaman buah jambu kanci kecil yang juga dapat dijadikan tanaman hias, cocok untuk taman dan kebun rumah. Harga pasar sekitar Rp60.000.', 8, 60000.00, 1.00, 'images/produk/jambu_kanci_tinggi_50cm.jpg', 1, 1, 0),
(102, 1, 'Jeruk Lemon', 'Jeruk Lemon adalah tanaman buah jeruk lemon kecil yang memberikan aroma segar dan cocok untuk taman maupun kebun rumah. Harga sekitar Rp60.000.', 7, 60000.00, 1.00, 'images/produk/jeruk_lemon.jpg', 1, 1, 0),
(103, 1, 'Asoka Singapur', 'Asoka Singapur adalah tanaman berbunga kecil yang populer sebagai pagar hidup, mudah dirawat dan sesuai untuk pemula. Harga sekitar Rp25.000.', 20, 25000.00, 0.20, 'images/produk/asoka_singapur.jpg', 1, 1, 0),
(104, 1, 'Sikas', 'Sikas (tinggi 70 cm) adalah tanaman sikas berukuran besar yang cocok sebagai tanaman hias eksklusif dengan perawatan khusus. Harga pasar sekitar Rp1.700.000.', 1, 1700000.00, 6.00, 'images/produk/sikas_tinggi_70cm.jpg', 1, 1, 0),
(105, 1, 'Kadaka Tempel', 'Kadaka Tempel adalah tanaman hias dengan daun menarik yang mudah dirawat dan sesuai untuk taman tropis maupun sebagai tanaman pagar hidup. Harga sekitar Rp35.000.', 10, 35000.00, 0.60, 'images/produk/kadaka_tempel.jpg', 1, 1, 0),
(106, 1, 'Pucuk Merah', 'Pucuk Merah (tinggi 250 cm) adalah tanaman pucuk merah tinggi yang sering digunakan sebagai pagar hidup atau dekorasi taman, memberikan warna cerah yang menarik dan menambah estetika lingkungan. Harga sekitar Rp350.000.', 4, 350000.00, 2.20, 'images/produk/pucuk_merah_tinggi_250cm.jpg', 1, 1, 0),
(107, 1, 'Kana', 'Kana (Canna indica) adalah tanaman tropis dengan daun lebar hijau cerah dan bunga besar berwarna merah, kuning, atau oranye yang mencolok. Tumbuh hingga 1–2 meter, cocok untuk taman dan halaman, tahan berbagai kondisi cuaca dan mudah dirawat sehingga sesuai untuk pemula. Harga pasar sekitar Rp30.000.', 25, 30000.00, 0.60, 'images/produk/kana.jpg', 1, 1, 0);

-- =====================================================
-- Insert Gambar Produk Tambahan (jpg dan png untuk 1-53)
-- =====================================================
INSERT INTO product_images (product_id, image_url, is_primary, interface_id) VALUES
(54, 'images/produk/jamani_dolar.jpg', 1, 1),
(54, 'images/produk/jamani_dolar.png', 0, 1),
(55, 'images/produk/dragon_sekel_atau_tengkorak.jpg', 1, 1),
(55, 'images/produk/dragon_sekel_atau_tengkorak.png', 0, 1),
(56, 'images/produk/pakis_kuning.jpg', 1, 1),
(56, 'images/produk/pakis_kuning.png', 0, 1),
(57, 'images/produk/kuping_gajah.jpg', 1, 1),
(57, 'images/produk/kuping_gajah.png', 0, 1),
(58, 'images/produk/cemara_ekor_tupay.jpg', 1, 1),
(58, 'images/produk/cemara_ekor_tupay.png', 0, 1),
(59, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.jpg', 1, 1),
(59, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_15.png', 0, 1),
(60, 'images/produk/puting_cabe.jpg', 1, 1),
(60, 'images/produk/puting_cabe.png', 0, 1),
(61, 'images/produk/cemara_perak.jpg', 1, 1),
(61, 'images/produk/cemara_perak.png', 0, 1),
(62, 'images/produk/bringin_korea_tinggi_2M.jpg', 1, 1),
(62, 'images/produk/bringin_korea_tinggi_2M.png', 0, 1),
(63, 'images/produk/gestrum_kuning.jpg', 1, 1),
(63, 'images/produk/gestrum_kuning.png', 0, 1),
(64, 'images/produk/brokoli_hijau.jpg', 1, 1),
(64, 'images/produk/brokoli_hijau.png', 0, 1),
(65, 'images/produk/siklok.jpg', 1, 1),
(65, 'images/produk/siklok.png', 0, 1),
(66, 'images/produk/sampang_dara.jpg', 1, 1),
(66, 'images/produk/sampang_dara.png', 0, 1),
(68, 'images/produk/teratai.jpg', 1, 1),
(68, 'images/produk/teratai.png', 0, 1),
(69, 'images/produk/airis_brazil.jpg', 1, 1),
(69, 'images/produk/airis_brazil.png', 0, 1),
(70, 'images/produk/batu_taman_hitam_putih.jpg', 1, 1),
(70, 'images/produk/batu_taman_hitam_putih.png', 0, 1),
(71, 'images/produk/maranti_bali.jpg', 1, 1),
(71, 'images/produk/maranti_bali.png', 0, 1),
(72, 'images/produk/kadaka_tanduk.jpg', 1, 1),
(72, 'images/produk/kadaka_tanduk.png', 0, 1),
(73, 'images/produk/jayen.jpg', 1, 1),
(73, 'images/produk/jayen.png', 0, 1),
(74, 'images/produk/alamanda_kuning.jpg', 1, 1),
(74, 'images/produk/alamanda_kuning.png', 0, 1),
(75, 'images/produk/sarbena_putih.jpg', 1, 1),
(75, 'images/produk/sarbena_putih.png', 0, 1),
(76, 'images/produk/sarbena_hijau.jpg', 1, 1),
(76, 'images/produk/sarbena_hijau.png', 0, 1),
(77, 'images/produk/pitalub_kecil.jpg', 1, 1),
(77, 'images/produk/pitalub_kecil.png', 0, 1),
(78, 'images/produk/aglonema_valentin.jpg', 1, 1),
(78, 'images/produk/aglonema_valentin.png', 0, 1),
(79, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.jpg', 1, 1),
(79, 'images/produk/pot_kapsul_hitam_coklat_hitam_diameter_35_tinggi_60.png', 0, 1),
(80, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.jpg', 1, 1),
(80, 'images/produk/pot_tanah_coklat_hitam_putih_diameter_30.png', 0, 1),
(81, 'images/produk/pot_hitam_diameter_40.jpg', 1, 1),
(81, 'images/produk/pot_hitam_diameter_40.png', 0, 1),
(82, 'images/produk/cemara_tretes_tinggi_120cm.jpg', 1, 1),
(82, 'images/produk/cemara_tretes_tinggi_120cm.png', 0, 1),
(83, 'images/produk/pitalub_tinggi_70cm.jpg', 1, 1),
(83, 'images/produk/pitalub_tinggi_70cm.png', 0, 1),
(84, 'images/produk/ketapang_kaligata_tinggi_60cm.jpg', 1, 1),
(84, 'images/produk/ketapang_kaligata_tinggi_60cm.png', 0, 1),
(85, 'images/produk/berekele.jpg', 1, 1),
(85, 'images/produk/berekele.png', 0, 1),
(86, 'images/produk/media_tanah.jpg', 1, 1),
(86, 'images/produk/media_tanah.png', 0, 1),
(87, 'images/produk/jamani_cobra.jpg', 1, 1),
(87, 'images/produk/jamani_cobra.png', 0, 1),
(88, 'images/produk/kamboja_jepang.jpg', 1, 1),
(88, 'images/produk/kamboja_jepang.png', 0, 1),
(89, 'images/produk/bringin_putih.jpg', 1, 1),
(89, 'images/produk/bringin_putih.png', 0, 1),
(90, 'images/produk/bromilian_baby_pink.jpg', 1, 1),
(90, 'images/produk/bromilian_baby_pink.png', 0, 1),
(91, 'images/produk/asoka_india.jpg', 1, 1),
(91, 'images/produk/asoka_india.png', 0, 1),
(92, 'images/produk/pandan_bali.jpg', 1, 1),
(92, 'images/produk/pandan_bali.png', 0, 1),
(93, 'images/produk/lidah_mertua.jpg', 1, 1),
(93, 'images/produk/lidah_mertua.png', 0, 1),
(94, 'images/produk/bringin_korea_micro.jpg', 1, 1),
(94, 'images/produk/bringin_korea_micro.png', 0, 1),
(95, 'images/produk/marigool.jpg', 1, 1),
(95, 'images/produk/marigool.png', 0, 1),
(96, 'images/produk/kaktus_koboy_tinggi_70cm.jpg', 1, 1),
(96, 'images/produk/kaktus_koboy_tinggi_70cm.png', 0, 1),
(97, 'images/produk/bonsai_gestrum(L).jpg', 1, 1),
(97, 'images/produk/bonsai_gestrum(L).png', 0, 1),
(98, 'images/produk/bonsai_gestrum(M).jpg', 1, 1),
(98, 'images/produk/bonsai_gestrum(M).png', 0, 1),
(99, 'images/produk/bonsai_cemara_udang.jpg', 1, 1),
(99, 'images/produk/bonsai_cemara_udang.png', 0, 1),
(100, 'images/produk/bunga_kertas.jpg', 1, 1),
(100, 'images/produk/bunga_kertas.png', 0, 1),
(101, 'images/produk/jambu_kanci_tinggi_50cm.jpg', 1, 1),
(101, 'images/produk/jambu_kanci_tinggi_50cm.png', 0, 1),
(102, 'images/produk/jeruk_lemon.jpg', 1, 1),
(102, 'images/produk/jeruk_lemon.png', 0, 1),
(103, 'images/produk/asoka_singapur.jpg', 1, 1),
(103, 'images/produk/asoka_singapur.png', 0, 1),
(104, 'images/produk/sikas_tinggi_70cm.jpg', 1, 1),
(104, 'images/produk/sikas_tinggi_70cm.png', 0, 1),
(105, 'images/produk/kadaka_tempel.jpg', 1, 1),
(105, 'images/produk/kadaka_tempel.png', 0, 1),
(106, 'images/produk/pucuk_merah_tinggi_250cm.jpg', 1, 1),
(106, 'images/produk/pucuk_merah_tinggi_250cm.png', 0, 1),
(107, 'images/produk/kana.jpg', 1, 1),
(107, 'images/produk/tanaman_kana.png', 0, 1);

-- =====================================================
-- Insert Banner
-- =====================================================
INSERT INTO banners (id, title, image, link, position, start_date, end_date, status, created_at, interface_id) VALUES
(1, 'Promo Tanaman Hias Juli', 'images/banners/banner1.png', '/products', 'homepage', '2025-07-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-07-23 01:33:18', 1),
(9, 'Promo Tanaman Hias Juli', 'images/banners/banner1.jpg', '/products', 'homepage', '2025-07-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-07-24 07:53:22', 1);

-- =====================================================
-- Insert Promosi
-- =====================================================
INSERT INTO promotions (id, promo_code, title, description, discount_type, discount_value, start_date, end_date, status, created_at, interface_id) VALUES
(2, 'PROMOJULI10', 'Diskon 10% untuk Tanaman Hias', 'Dapatkan diskon 10% untuk pembelian minimal Rp200.000 tanaman hias.', 'percent', 10.00, '2025-07-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-07-24 07:53:22', 1),
(3, 'PROMO-SV294G', 'Promo Newsletter untuk wdawdaaw02@gmail.com', 'Promo khusus subscriber newsletter.', 'percent', 10.00, '2025-07-24 11:43:32', '2025-08-23 11:43:32', 1, '2025-07-24 11:43:32', 1);


-- Hapus metode yang tidak dibutuhkan
DELETE FROM payment_methods WHERE code NOT IN ('CASH', 'COD_QRIS', 'QRIS', 'EWALLET');

-- Update nama dan deskripsi
UPDATE payment_methods SET
    name = 'Uang Tunai di Tempat',
    description = 'Bayar langsung secara tunai kepada kurir saat barang diterima di alamat tujuan.'
WHERE code = 'CASH';

UPDATE payment_methods SET
    name = 'COD dengan QRIS/E-Wallet',
    description = 'Bayar di tempat tujuan melalui QRIS atau E-Wallet (Scan QR, OVO, GoPay, DANA, dll) kepada kurir.'
WHERE code = 'COD_QRIS';

UPDATE payment_methods SET
    name = 'Pembayaran QRIS',
    description = 'Bayar secara instan melalui QRIS dari semua aplikasi e-wallet. Transaksi digital, aman, dan cepat.'
WHERE code = 'QRIS';

UPDATE payment_methods SET
    name = 'E-Wallet',
    description = 'Pembayaran digital melalui OVO, GoPay, DANA, dan e-wallet lainnya. Transaksi instan dan tercatat.'
WHERE code = 'EWALLET';

-- Aktifkan hanya 4 metode ini
UPDATE payment_methods SET status = 1 WHERE code IN ('CASH', 'COD_QRIS', 'QRIS', 'EWALLET');

-- =====================================================
-- Catatan:
-- Simpan gambar produk pada: C:\laragon\www\azka-garden\public\images\produk
-- Simpan gambar banner pada: C:\laragon\www\azka-garden\public\images\banners
-- Field image_url (produk) dan image (banner) harus gunakan path relatif seperti di atas.
-- Di Blade view, gunakan asset($product->image_url)
-- Untuk gallery/slider di detail, ambil gambar dari tabel product_images (semua .jpg, .png per produk).
-- =====================================================