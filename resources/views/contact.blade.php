@extends('layouts.app')

@section('title', 'Kontak Kami - Azka Garden')

@section('content')
<div class="container max-w-6xl px-4 py-12 mx-auto">
  <h1 class="mb-8 text-4xl font-extrabold text-green-700">Kontak Kami</h1>

  <div class="flex flex-col gap-12 md:flex-row">
    {{-- Form Kontak --}}
    <div class="md:w-1/2">
      @if(session('success'))
        <div id="success-message" class="px-4 py-3 mb-6 text-green-800 transition-opacity duration-500 bg-green-100 border border-green-300 rounded-lg">
          {{ session('success') }}
        </div>
      @endif

      <form action="{{ route('contact.submit') }}" method="POST" class="relative p-6 space-y-6 bg-white border border-green-200 shadow-md rounded-xl" novalidate>
        @csrf

        {{-- Nama --}}
        <div>
          <label for="name" class="block mb-2 font-semibold text-green-800">Nama Lengkap</label>
          <input id="name" name="name" type="text" autocomplete="name" value="{{ old('name') }}" required
            class="w-full rounded-md p-3 text-green-900 placeholder-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 border {{ $errors->has('name') ? 'border-red-500' : 'border-green-300' }}"
            placeholder="Masukkan nama lengkap Anda" />
          @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Email --}}
        <div>
          <label for="email" class="block mb-2 font-semibold text-green-800">Alamat Email</label>
          <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required
            class="w-full rounded-md p-3 text-green-900 placeholder-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 border {{ $errors->has('email') ? 'border-red-500' : 'border-green-300' }}"
            placeholder="contoh@domain.com" />
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Nomor Telepon --}}
        <div>
          <label for="phone" class="block mb-2 font-semibold text-green-800">Nomor Telepon (opsional)</label>
          <input id="phone" name="phone" type="tel" autocomplete="tel" value="{{ old('phone') }}"
            class="w-full rounded-md p-3 text-green-900 placeholder-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 border {{ $errors->has('phone') ? 'border-red-500' : 'border-green-300' }}"
            placeholder="0812xxxxxxx" />
          @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Pesan --}}
        <div class="relative">
          <label for="message" class="block mb-2 font-semibold text-green-800">Pesan Anda</label>
          <textarea id="message" name="message" autocomplete="off" rows="5" required
            class="w-full rounded-md p-3 text-green-900 placeholder-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 border {{ $errors->has('message') ? 'border-red-500' : 'border-green-300' }}"
            placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>

          {{-- Emoji tombol --}}
          <button type="button" id="emoji-single"
            class="absolute text-2xl select-none right-2 bottom-2"
            title="Tambahkan emoji 😀">😀</button>

          @error('message')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        {{-- Submit --}}
        <div>
          <button type="submit"
            class="w-full py-3 font-semibold text-white transition-shadow bg-green-600 rounded-lg shadow-md hover:bg-green-700 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-green-400">
            Kirim Pesan
          </button>
        </div>
      </form>
    </div>

    {{-- Informasi Kontak --}}
    <div class="text-green-900 md:w-1/2">
      <h2 class="mb-3 text-2xl font-bold text-green-700">Informasi Kontak Azka Garden</h2>
      <p>Azka Garden adalah usaha hortikultura keluarga Pak Hendrik yang memadukan kios tanaman di Depok, Jawa Barat dengan kanal edukasi daring. Kios pusat berlokasi di <strong>HRQH+3VP, Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok</strong>.</p>

      <p class="mt-4">
        <strong>Nomor Telepon / WhatsApp:</strong> <a href="tel:+6289635086182" class="text-green-600 hover:underline">0896-3508-6182</a><br>
        <strong>Jam Operasional:</strong> 24 jam setiap hari<br>
        <strong>Jenis Usaha:</strong> Pembibitan dan persediaan untuk kebun, penjualan tanaman hias, bunga potong, dan perlengkapan taman.
      </p>

      <p class="mt-4">
        <strong>Saluran Digital Resmi:</strong><br>
        <a href="https://www.youtube.com/channel/UCuAUD9jzepl1iay_eIlDgKw" target="_blank" class="text-green-600 hover:underline">YouTube - Azka Garden Indonesia</a><br>
        <a href="https://www.facebook.com/people/Azka-Garden-Indonesia/100063831022523/" target="_blank" class="text-green-600 hover:underline">Facebook - Azka Garden Indonesia (Sleman)</a><br>
        <a href="https://www.instagram.com/azka_garden/" target="_blank" class="text-green-600 hover:underline">Instagram - @azka_garden</a><br>
        <a href="https://www.tiktok.com/@azkagarden1" target="_blank" class="text-green-600 hover:underline">TikTok - @azkagarden1</a>
      </p>

      <p class="mt-4">
        <strong>Peta Lokasi Kios:</strong><br>
        <a href="https://maps.app.goo.gl/j5AuLF1AZ3VVgovcA" target="_blank" class="text-green-600 hover:underline">Lihat di Google Maps</a>
      </p>

      <p class="mt-6 text-sm italic text-green-700">
        Azka Garden menggabungkan penjualan tanaman, jasa lanskap, dan produksi konten edukatif. Terletak di Depok, Azka Garden beroperasi 24 jam dan melayani pelanggan via telepon maupun konsultasi WhatsApp.
      </p>
    </div>
  </div>
</div>
@endsection

{{-- JavaScript untuk menambahkan emoji ke textarea --}}
@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const emojiBtn = document.getElementById('emoji-single');
    const messageTextArea = document.getElementById('message');
    
    emojiBtn.addEventListener('click', function() {
      const emoji = this.textContent;
      const startPos = messageTextArea.selectionStart;
      const endPos = messageTextArea.selectionEnd;
      const currentValue = messageTextArea.value;

      // Insert the emoji at the current cursor position
      messageTextArea.value = currentValue.substring(0, startPos) + emoji + currentValue.substring(endPos);
      
      // Set the focus back to the textarea and reposition the cursor
      messageTextArea.focus();
      const newCursorPos = startPos + emoji.length;
      messageTextArea.selectionStart = messageTextArea.selectionEnd = newCursorPos;
    });
  });
</script>
@endsection