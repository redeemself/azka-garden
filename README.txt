Berikut ringkasan lengkap dan urut langkah benar menambahkan folder lokal azka-garden beserta isinya ke GitHub repository redeemself/azka-garden, termasuk opsi push --force bila diperlukan:

1. Pastikan Repository Sudah Ada di GitHub
Buat repository azka-garden di GitHub jika belum ada.

Jangan centang opsi “Initialize with README” supaya tidak ada file awal di remote.

2. Buka Terminal (misal Terminal Laragon)
Arahkan ke folder induk tempat azka-garden berada.

bash
Copy
cd path\to\azka-garden
3. Inisialisasi Git (jika belum pernah git init)
bash
Copy
git init
4. Tambahkan Remote Origin (hubungkan ke repo GitHub)
bash
Copy
git remote add origin https://github.com/redeemself/azka-garden.git
Jika sudah ada remote dan perlu update URL:

bash
Copy
git remote set-url origin https://github.com/redeemself/azka-garden.git
5. Tambahkan Semua File dan Folder ke Staging Area
bash
Copy
git add .
6. Commit Perubahan dengan Pesan Jelas
bash
Copy
git commit -m "Initial commit: add azka-garden project"
7. Push ke Repository GitHub
Jika repo GitHub masih kosong (fresh), cukup:

bash
Copy
git branch -M main
git push -u origin main
Jika remote sudah berisi commit (misalnya README), dan muncul error non-fast-forward, lakukan pull dan rebase dulu:

bash
Copy
git pull origin main --rebase
# Selesaikan konflik jika ada, lalu:
git push -u origin main
Jika kamu yakin ingin menimpa isi remote dengan isi lokal (hati-hati!), gunakan force push:

bash
Copy
git push -u origin main --force
Catatan Penting
Gunakan push --force hanya jika benar-benar yakin, karena dapat menghapus riwayat di remote dan merugikan kolaborator lain.

Pastikan sudah login GitHub atau sudah atur credential manager di terminal (disarankan Git Credential Manager).

Jangan commit folder node_modules atau file yang tidak perlu; gunakan .gitignore untuk mengecualikan.

Jika muncul error saat push/pull, baca pesan error dan sesuaikan solusi (misal hapus file terkunci, lakukan rebase, dsb).

Cara Update File/Folder Baru di Masa Depan
Jika ada perubahan file atau file baru:

Edit atau tambah file di folder lokal azka-garden.

Jalankan perintah berikut:

bash
Copy
git add .
git commit -m "deskripsi singkat perubahan"
git push origin main
Ringkasan Singkat Semua Langkah
bash
Copy
cd path\to\azka-garden
git init                      # jika belum pernah init
git remote add origin https://github.com/redeemself/azka-garden.git
git add .
git commit -m "Initial commit: add azka-garden project"
git branch -M main
git push -u origin main --force   # pakai --force jika remote sudah berisi commit dan kamu ingin overwrite
