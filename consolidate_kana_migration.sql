-- Migration to consolidate duplicate Kana entries
-- Step 1: Remove the duplicate entry with kana.png image
DELETE FROM products 
WHERE name = 'Kana' AND image_url = 'images/produk/kana.png';

-- Step 2: Update the remaining entry with kana.jpg to match requirements
UPDATE products 
SET 
    description = 'Canna indica adalah tanaman tropis dengan daun lebar hijau cerah dan bunga besar berwarna merah, kuning, atau oranye yang mencolok. Tumbuh hingga 1–2 meter, cocok untuk taman dan halaman, tahan berbagai kondisi cuaca dan mudah dirawat sehingga sesuai untuk pemula. Harga pasar sekitar Rp30.000.',
    stock = 25,
    price = 'Rp30,000',
    weight = 0.60,
    updated_at = CURRENT_TIMESTAMP
WHERE name = 'Kana' AND image_url = 'images/produk/kana.jpg';