@extends('layouts.app')

@section('title', 'Kebijakan Privasi - Azka Garden')

@section('content')
<section class="py-12 main-content-offset">
  <div class="container max-w-4xl px-4 mx-auto space-y-10">
    {{-- Judul --}}
    <h1 class="flex items-center gap-2 mb-4 text-3xl font-extrabold text-green-700 md:text-4xl">
      <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z"/>
      </svg>
      Kebijakan Privasi
    </h1>

    {{-- Ringkasan dan Kepatuhan --}}
    <div class="space-y-4 leading-relaxed text-justify">
      <p style="text-indent:1.27cm;">
        Azka Garden berkomitmen melindungi data pribadi pengguna sesuai Undang-Undang PDP 2022, UU ITE, GDPR, serta standar PCI DSS untuk keamanan pembayaran. Kebijakan ini berlaku untuk seluruh layanan situs, transaksi, dan komunikasi digital Azka Garden.
      </p>
    </div>

    {{-- Data, Tujuan, Hak, Keamanan --}}
    <div class="grid gap-8 md:grid-cols-2">
      <div>
        <h2 class="mt-2 mb-2 text-xl font-semibold text-green-700">1. Data yang Kami Kumpulkan</h2>
        <ul class="ml-4 space-y-2 list-disc list-inside">
          <li>Identitas: nama, alamat, email, nomor telepon</li>
          <li>Transaksi: pembelian, pembayaran, riwayat order</li>
          <li>Teknis: IP address, jenis perangkat, browser, log server</li>
          <li>Preferensi pemasaran & newsletter</li>
        </ul>
        <h2 class="mt-6 mb-2 text-xl font-semibold text-green-700">2. Tujuan Penggunaan Data</h2>
        <ul class="ml-4 space-y-2 list-disc list-inside">
          <li>Memproses & mengirim pesanan</li>
          <li>Mengelola akun dan layanan pelanggan</li>
          <li>Mengirim promosi (opsional, dapat berhenti kapan saja)</li>
          <li>Analisis performa situs & pengembangan</li>
          <li>Pemenuhan kewajiban hukum (pajak, pembukuan)</li>
        </ul>
      </div>
      <div>
        <h2 class="mt-2 mb-2 text-xl font-semibold text-green-700">3. Keamanan Data</h2>
        <ul class="ml-4 space-y-2 list-disc list-inside">
          <li>Enkripsi TLS 1.3 untuk komunikasi</li>
          <li>Password terenkripsi Argon2id + pepper</li>
          <li>Audit keamanan triwulanan, firewall ISO 27001</li>
          <li>Pembayaran via gateway PCI DSS, data kartu tidak disimpan</li>
          <li>Penyimpanan data maksimal 5 tahun (PP 80/2019)</li>
        </ul>
        <h2 class="mt-6 mb-2 text-xl font-semibold text-green-700">4. Hak dan Pilihan Anda</h2>
        <ul class="ml-4 space-y-2 list-disc list-inside">
          <li>Akses, koreksi, hapus data</li>
          <li>Membatasi atau menolak pemrosesan data</li>
          <li>Menarik persetujuan kapan saja</li>
          <li>Portabilitas data & permintaan via DPO (dpo@azkagarden.id)</li>
          <li>Respons maksimal 30 hari (UU PDP Pasal 6–12)</li>
        </ul>
      </div>
    </div>

    {{-- Notifikasi, Pelanggaran, Transfer Data --}}
    <div class="mt-8 space-y-3 text-sm text-justify">
      <div class="p-4 border border-green-200 rounded-xl bg-green-50/60">
        <strong>Pemberitahuan Pelanggaran:</strong> Jika terjadi insiden pelanggaran data, pemberitahuan akan dikirim dalam 72 jam kepada pengguna dan Kominfo sesuai GDPR & UU PDP.
      </div>
      <div class="p-4 border border-green-200 rounded-xl bg-green-50/60">
        <strong>Transfer Data ke Luar Negeri:</strong> Hanya ke pihak dengan perlindungan setara GDPR, atau melalui kontrak model standar.
      </div>
    </div>

    {{-- Kontak & Pertanyaan --}}
    <div class="p-6 mt-10 border border-green-200 shadow-sm bg-green-50/80 rounded-2xl">
      <h2 class="flex items-center gap-3 mb-3 text-xl font-semibold text-green-700">
        <svg class="text-green-500 w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
          <circle cx="12" cy="11" r="3" />
        </svg>
        Kontak & DPO
      </h2>
      <ul>
        <li><b>Email:</b> <a href="mailto:info@azkagarden.com" class="text-green-700 underline hover:text-green-900">info@azkagarden.com</a></li>
        <li><b>Telepon:</b> <a href="tel:089635086182" class="text-green-700 underline hover:text-green-900">0896-3508-6182</a></li>
        <li><b>DPO:</b> <a href="mailto:dpo@azkagarden.id" class="text-green-700 underline hover:text-green-900">dpo@azkagarden.id</a></li>
      </ul>
    </div>

    {{-- Tombol Persetujuan --}}
    <div class="p-6 space-y-4 text-center bg-white border border-green-200 shadow-md rounded-2xl">
      <p class="mb-4 text-lg text-gray-700">Untuk melanjutkan menggunakan situs ini, Anda harus menyetujui Kebijakan Privasi.</p>
      @if(session('success') || request()->cookie('policy_accepted'))
        <button disabled class="px-8 py-3 font-semibold text-white bg-green-400 rounded-lg shadow cursor-not-allowed">
          Telah Menyetujui
        </button>
        <form method="POST" action="{{ route('policy.reset') }}">
          @csrf
          <button type="submit" class="px-8 py-3 mt-4 font-semibold text-white bg-red-600 rounded-lg shadow hover:bg-red-700">
            Reset Persetujuan Kebijakan Privasi
          </button>
        </form>
      @else
        <form method="POST" action="{{ route('policy.accept') }}">
          @csrf
          <button type="submit" class="px-8 py-3 font-semibold text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
            Saya Setuju
          </button>
        </form>
      @endif
    </div>

    {{-- Footer Kecil --}}
    <div class="mt-12 text-sm text-center text-gray-500">
      &copy; {{ date('Y') }} Azka Garden. Semua hak dilindungi.
    </div>
  </div>
</section>
@endsection
