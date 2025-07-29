@extends('layouts.app')

@section('title', 'Kontak Kami - Azka Garden')

@section('content')
<div class="container max-w-6xl px-4 py-12 mx-auto">
  <h1 class="mb-8 text-4xl font-extrabold text-green-700">Kontak Kami</h1>
  <div class="flex flex-col gap-12 md:flex-row">
    {{-- Form Kontak --}}
    <div class="md:w-1/2">
        @if(session('success'))
        <div id="success-message" class="flex items-center gap-2 px-4 py-3 mb-6 text-green-800 transition-opacity duration-500 bg-green-100 border border-green-300 rounded-lg">
            <span class="text-2xl">✅</span>
            <span>{{ session('success') }}</span>
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

        {{-- Pesan dengan Emoji Picker Lengkap --}}
        <div class="relative">
          <label for="message" class="block mb-2 font-semibold text-green-800">Pesan Anda</label>
          <div class="relative textarea-wrap">
            <textarea id="message" name="message" autocomplete="off" rows="5" required
              class="w-full rounded-md p-3 text-green-900 placeholder-green-400 focus:outline-none focus:ring-2 focus:ring-green-500 border {{ $errors->has('message') ? 'border-red-500' : 'border-green-300' }}"
              placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
            <button type="button" id="emojiToggleBtn"
              class="absolute emoji-toggle-btn right-2 bottom-2" title="Buka Emoji">&#128525;</button>
          </div>
          <div class="mt-2 emoji-picker-area">
            <div class="emoji-popup" id="emojiPopup" style="display:none;">
              <div class="emoji-tabs">
                <button type="button" class="emoji-tab active" data-tab="emoji">😊</button>
                <button type="button" class="emoji-tab" data-tab="kaomoji">(ᵔᴥᵔ)</button>
                <button type="button" class="emoji-tab" data-tab="gif">GIF</button>
                <button type="button" class="emoji-tab" data-tab="sticker">🖼️</button>
              </div>
              <input type="text" class="search-input" id="emojiSearch" placeholder="Cari emoji, kaomoji, GIF, stiker...">
              <div class="emoji-list" id="tab-emoji" style="display:flex;"></div>
              <div class="kaomoji-list" id="tab-kaomoji" style="display:none;"></div>
              <div class="gif-list" id="tab-gif" style="display:none;"></div>
              <div class="sticker-list" id="tab-sticker" style="display:none;"></div>
              <div class="emoji-label" id="emojiLabel"></div>
              <div class="emoji-error" id="emojiError" style="display:none;">Gagal memuat data emoji. Silakan refresh atau cek koneksi!</div>
            </div>
          </div>
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
    <div class="px-4 py-2 text-green-900 md:w-1/2">
        <h2 class="mb-3 text-2xl font-bold text-green-700">Informasi Kontak Azka Garden</h2>
        <p>Azka Garden adalah usaha hortikultura keluarga Pak Hendrik yang memadukan kios tanaman di Depok, Jawa Barat dengan kanal edukasi daring. Kios pusat berlokasi di <strong>HRQH+3VP, Jalan Raya KSU, Kelurahan Tirtajaya, Kecamatan Sukmajaya, Kota Depok</strong>.</p>
        <p class="mt-4">
            <strong>Nomor Telepon / WhatsApp:</strong> <a href="tel:+6289635086182" class="text-green-600 hover:underline">0896-3508-6182</a><br>
            <strong>Jam Operasional:</strong> 24 jam setiap hari<br>
            <strong>Jenis Usaha:</strong> Pembibitan dan persediaan untuk kebun, penjualan tanaman hias, bunga potong, dan perlengkapan taman.
        </p>
        <p class="mt-4">
            <strong>Kontak & Marketplace Resmi:</strong><br>
            <a href="https://wa.me/6289635086182" target="_blank" class="flex items-center gap-1 mt-1 text-green-600 hover:underline">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                WhatsApp - 0896-3508-6182
            </a><br>
            <a href="https://www.tokopedia.com/hendrikfloris" target="_blank" class="flex items-center gap-1 mt-1 text-green-600 hover:underline">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 1.25C6.072 1.25 1.25 6.072 1.25 12S6.072 22.75 12 22.75 22.75 17.928 22.75 12 17.928 1.25 12 1.25zm0 1.5c5.109 0 9.25 4.141 9.25 9.25s-4.141 9.25-9.25 9.25S2.75 17.109 2.75 12 6.891 2.75 12 2.75z"/>
                    <path d="M8.797 15.333c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.937.744-.937 1.658s.42 1.658.937 1.658.938-.744.938-1.658-.421-1.658-.938-1.658zm6.406 4.816c-1.375 0-2.5-1.419-2.5-3.158 0-1.74 1.125-3.158 2.5-3.158s2.5 1.419 2.5 3.158c0 1.74-1.125 3.158-2.5 3.158zm0-4.816c-.517 0-.938.744-.938 1.658s.421 1.658.938 1.658.937-.744.937-1.658-.42-1.658-.937-1.658z"/>
                </svg>
                Tokopedia - Toko Bunga Hendrik
            </a>
        </p>
        <p class="mt-4">
            <strong>Peta Lokasi Kios:</strong><br>
            <a href="https://maps.app.goo.gl/j5AuLF1AZ3VVgovcA" target="_blank" class="text-green-600 hover:underline">Lihat di Google Maps</a>
        </p>
        <p class="mt-6 text-sm italic text-green-700">
            Azka Garden menggabungkan penjualan tanaman, jasa lanskap, dan produksi konten edukatif. Terletak di Depok, Azka Garden beroperasi 24 jam dan melayani pelanggan via telepon maupun konsultasi WhatsApp.
        </p>
        <div class="flex flex-col items-center justify-center gap-4 my-10 md:flex-row md:gap-6">
            <a href="{{ route('blog.index') }}"
            class="inline-block w-full px-8 py-3 font-semibold text-center text-green-700 bg-green-100 rounded-lg shadow md:w-auto hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400"
            aria-label="Lihat halaman blog Azka Garden">Blog</a>
            <a href="{{ route('artikel.index') }}"
            class="inline-block w-full px-8 py-3 font-semibold text-center text-green-700 bg-green-100 rounded-lg shadow md:w-auto hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400"
            aria-label="Lihat semua artikel Azka Garden">Artikel</a>
            <a href="{{ route('faq') }}"
            class="inline-block w-full px-8 py-3 font-semibold text-center text-green-700 bg-green-100 rounded-lg shadow md:w-auto hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-green-400"
            aria-label="Lihat halaman FAQ Azka Garden">FAQ</a>
        </div>
    </div>
  </div>
</div>

{{-- Tambahkan style CSS Emoji Picker --}}
<style>
  body { font-family: sans-serif; background: #f7fafc; margin: 0; padding: 0; }
  .textarea-wrap { position: relative; width: 100%; }
  textarea { width: 100%; min-height: 120px; padding: 14px 48px 14px 14px; font-size: 1.1rem; border-radius: 10px; border: 1px solid #ccc; resize: vertical; box-sizing: border-box; }
  .emoji-toggle-btn { position: absolute; right: 12px; bottom: 12px; width: 40px; height: 40px; background: #fff; border: 2px solid #eee; border-radius: 50%; box-shadow: 0 3px 8px rgba(0,0,0,0.07); font-size: 1.5rem; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center; transition: box-shadow 0.2s; }
  .emoji-toggle-btn:hover { box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
  .emoji-picker-area { position: relative; width: 100%; margin-top: 14px; z-index: 2; display: flex; justify-content: flex-start; }
  .emoji-popup { width: 100%; max-width: 800px; min-width: 320px; background: #fff; border: 1px solid #ddd; border-radius: 16px; box-shadow: 0 8px 28px rgba(0,0,0,0.18); padding: 16px 18px 24px 18px; z-index: 99; box-sizing: border-box; }
  .emoji-tabs { display: flex; gap: 8px; margin-bottom: 8px; }
  .emoji-tab { padding: 6px 14px; border: none; background: #f6f6f6; border-radius: 8px; cursor: pointer; font-size: 1.1rem; transition: background 0.12s; }
  .emoji-tab.active { background: #e0f7fa; font-weight: bold; }
  .search-input { width: 100%; padding: 6px 10px; font-size: 1rem; border-radius: 5px; border: 1px solid #ddd; margin-bottom: 4px; box-sizing: border-box; }
  .emoji-list, .kaomoji-list, .sticker-list, .gif-list { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; max-height: 220px; overflow-y: auto; }
  .emoji-btn, .kaomoji-btn, .sticker-btn, .gif-btn { background: none; border: none; font-size: 2rem; cursor: pointer; transition: transform 0.14s; }
  .emoji-btn:hover, .kaomoji-btn:hover, .sticker-btn:hover, .gif-btn:hover { transform: scale(1.18); }
  .kaomoji-btn { font-size: 1.25rem; padding: 2px 12px; background: #f8f8f8; border-radius: 6px; font-family: inherit; }
  .gif-btn img, .sticker-btn img { border-radius: 8px; vertical-align: middle; background: #f2f2f2; width: 60px; height: 60px; object-fit: cover; display: block; }
  .emoji-label { font-size: 1em; color: #888; margin-top: 16px; margin-bottom: 2px; text-align: left; width: 100%; display: block; }
  .emoji-error { text-align: center; color: #f44; font-size: 1em; margin-top: 32px; }
  @media (max-width: 900px) { .emoji-popup { max-width: 99vw; } }
  @media (max-width: 480px) { .emoji-popup { padding: 8px 2px; font-size: 0.95em;} .gif-btn img, .sticker-btn img { width: 44px; height: 44px; } }
</style>

{{-- Tambahkan JavaScript Emoji Picker --}}
<script>
  // Data emoji, kaomoji, gif, sticker (use previous JS arrays: emojiData, kaomojiArr, gifArr, stickerArr)
  // See previous answers for complete arrays. For brevity, arrays are omitted here.
    const emojiData = [
    {emoji:"😀",name:"grinning face",keywords:"smile happy"},
    {emoji:"😁",name:"beaming face with smiling eyes",keywords:"smile grinning happy"},
    {emoji:"😂",name:"face with tears of joy",keywords:"laugh joy happy"},
    {emoji:"🤣",name:"rolling on the floor laughing",keywords:"rolling laugh"},
    {emoji:"😃",name:"grinning face with big eyes",keywords:"smile bigeyes"},
    {emoji:"😄",name:"grinning face with smiling eyes",keywords:"smile eyes"},
    {emoji:"😅",name:"grinning face with sweat",keywords:"smile sweat"},
    {emoji:"😆",name:"grinning squinting face",keywords:"smile squint"},
    {emoji:"😉",name:"winking face",keywords:"wink"},
    {emoji:"😊",name:"smiling face with smiling eyes",keywords:"smile eyes"},
    {emoji:"😍",name:"smiling face with heart-eyes",keywords:"love heart eyes"},
    {emoji:"😘",name:"face blowing a kiss",keywords:"kiss love"},
    {emoji:"😗",name:"kissing face",keywords:"kiss"},
    {emoji:"😙",name:"kissing face with smiling eyes",keywords:"kiss smile"},
    {emoji:"😚",name:"kissing face with closed eyes",keywords:"kiss closed"},
    {emoji:"🙂",name:"slightly smiling face",keywords:"smile slight"},
    {emoji:"🤗",name:"hugging face",keywords:"hug"},
    {emoji:"🤩",name:"star-struck",keywords:"star bintang"},
    {emoji:"🤔",name:"thinking face",keywords:"think"},
    {emoji:"🤨",name:"face with raised eyebrow",keywords:"curious eyebrow"},
    {emoji:"😐",name:"neutral face",keywords:"neutral"},
    {emoji:"😑",name:"expressionless face",keywords:"expressionless datar"},
    {emoji:"😶",name:"face without mouth",keywords:"mute"},
    {emoji:"🙄",name:"face with rolling eyes",keywords:"rolling eyes"},
    {emoji:"😏",name:"smirking face",keywords:"smirk"},
    {emoji:"😣",name:"persevering face",keywords:"persevere"},
    {emoji:"😥",name:"sad but relieved face",keywords:"sad relieved"},
    {emoji:"😮",name:"face with open mouth",keywords:"surprised open"},
    {emoji:"🤐",name:"zipper-mouth face",keywords:"zipper mouth"},
    {emoji:"😯",name:"hushed face",keywords:"hushed"},
    {emoji:"😪",name:"sleepy face",keywords:"sleepy"},
    {emoji:"😫",name:"tired face",keywords:"tired"},
    {emoji:"😴",name:"sleeping face",keywords:"sleep"},
    {emoji:"😌",name:"relieved face",keywords:"relieved"},
    {emoji:"😛",name:"face with tongue",keywords:"tongue"},
    {emoji:"😜",name:"winking face with tongue",keywords:"wink tongue"},
    {emoji:"😝",name:"squinting face with tongue",keywords:"squint tongue"},
    {emoji:"🤤",name:"drooling face",keywords:"drool"},
    {emoji:"😒",name:"unamused face",keywords:"unamused"},
    {emoji:"😓",name:"downcast face with sweat",keywords:"downcast sweat"},
    {emoji:"😔",name:"pensive face",keywords:"pensive"},
    {emoji:"😕",name:"confused face",keywords:"confused"},
    {emoji:"🙃",name:"upside-down face",keywords:"upside down"},
    {emoji:"🤑",name:"money-mouth face",keywords:"money"},
    {emoji:"😲",name:"astonished face",keywords:"astonished"},
    {emoji:"☹️",name:"frowning face",keywords:"frown"},
    {emoji:"🙁",name:"slightly frowning face",keywords:"slight frown"},
    {emoji:"😖",name:"confounded face",keywords:"confounded"},
    {emoji:"😞",name:"disappointed face",keywords:"disappointed"},
    {emoji:"😟",name:"worried face",keywords:"worried"},
    {emoji:"😤",name:"face with steam from nose",keywords:"steam nose"},
    {emoji:"😢",name:"crying face",keywords:"cry"},
    {emoji:"😭",name:"loudly crying face",keywords:"cry loud"},
    {emoji:"😦",name:"frowning face with open mouth",keywords:"frown open"},
    {emoji:"😧",name:"anguished face",keywords:"anguished"},
    {emoji:"😨",name:"fearful face",keywords:"fearful"},
    {emoji:"😩",name:"weary face",keywords:"weary"},
    {emoji:"🤯",name:"exploding head",keywords:"exploding"},
    {emoji:"😬",name:"grimacing face",keywords:"grimace"},
    {emoji:"😰",name:"anxious face with sweat",keywords:"anxious sweat"},
    {emoji:"😱",name:"face screaming in fear",keywords:"scream fear"},
    {emoji:"🥵",name:"hot face",keywords:"hot"},
    {emoji:"🥶",name:"cold face",keywords:"cold"},
    {emoji:"😳",name:"flushed face",keywords:"flushed"},
    {emoji:"🤪",name:"zany face",keywords:"zany"},
    {emoji:"😵",name:"dizzy face",keywords:"dizzy"},
    {emoji:"😡",name:"pouting face",keywords:"pout angry"},
    {emoji:"😠",name:"angry face",keywords:"angry"},
    {emoji:"🤬",name:"face with symbols on mouth",keywords:"symbols mouth"},
    {emoji:"😷",name:"face with medical mask",keywords:"mask medical"},
    {emoji:"🤒",name:"face with thermometer",keywords:"thermometer"},
    {emoji:"🤕",name:"face with head-bandage",keywords:"head bandage"},
    {emoji:"🤢",name:"nauseated face",keywords:"nauseated"},
    {emoji:"🤮",name:"face vomiting",keywords:"vomit"},
    {emoji:"🥴",name:"woozy face",keywords:"woozy"},
    {emoji:"😇",name:"smiling face with halo",keywords:"halo"},
    {emoji:"🥳",name:"partying face",keywords:"party"},
    {emoji:"🥺",name:"pleading face",keywords:"pleading"},
    {emoji:"🤠",name:"cowboy hat face",keywords:"cowboy"},
    {emoji:"🤡",name:"clown face",keywords:"clown"},
    {emoji:"🤥",name:"lying face",keywords:"lying"},
    {emoji:"🤫",name:"shushing face",keywords:"shushing"},
    {emoji:"🤭",name:"face with hand over mouth",keywords:"hand mouth"},
    {emoji:"🧐",name:"face with monocle",keywords:"monocle"},
    {emoji:"🥸",name:"disguised face",keywords:"disguise"},
    {emoji:"😈",name:"smiling face with horns",keywords:"devil horn"},
    {emoji:"👻",name:"ghost",keywords:"ghost"}
  ];

  const kaomojiArr = [
    "( ͡° ͜ʖ ͡°)", "(ᵔᴥᵔ)", "(•‿•)", "(｡◕‿◕｡)", "(＾▽＾)", "(♥‿♥)", "(^人^)", "(⌒‿⌒)",
    "(ノ^_^)ノ", "(✿◠‿◠)", "(￣▽￣)", "(≧◡≦)", "(∩^o^)⊃━☆ﾟ.*･｡ﾟ", "ʕ•ᴥ•ʔ",
    "(☞ﾟヮﾟ)☞", "¯\\_(ツ)_/¯", "(¬‿¬)", "(◕‿◕)", "(╯°□°）╯︵ ┻━┻",
    "(^_^)", "(≧ω≦)", "(＾ｖ＾)", "(>‿◠)", "(◕‿◕✿)", "(✧ω✧)", "(•ω•)", "(˘︶˘).｡*♡",
    "(≧▽≦)", "(^o^)", "(^.^)", "(･ω･)", "(´∀｀)", "(⌒▽⌒)", "(☆▽☆)", "(^▽^)",
    "(¬_¬)", "(•_•)", "(ʘ‿ʘ)", "(⊙_⊙)", "(⊙.⊙)", "(¬‿¬)", "(¬‿¬)", "(๑•́ ₃ •̀๑)",
    "(｡•́︿•̀｡)", "(╥﹏╥)", "(ಥ﹏ಥ)", "(T_T)", "(ಥ_ಥ)", "(︶︹︺)", "(◞‸◟；)",
    "(눈_눈)", "(¬_¬)", "(ʘ‿ʘ)", "(ノಠ益ಠ)ノ", "(ง'̀-'́)ง", "(ಥ_ಥ)", "(ノ◕ヮ◕)ノ*:･ﾟ✧",
    "(¬‿¬)", "(¬_¬)", "(¬‿¬)", "(´∩｡• ᵕ •｡∩`)", "(∩^o^)⊃━☆ﾟ.*･｡ﾟ", "(∩｀-´)⊃━炎炎炎炎炎炎炎炎炎",
    "(^_^;)", "(￣︶￣)", "(งツ)ว", "(¬‿¬)", "(¬_¬)", "(ᗒᗣᗕ)՞", "(ᗒᗨᗕ)", "(⊙ω⊙)", "(⊙▽⊙)", "(⊙ ͜ʖ⊙)", "(∩╹□╹∩)"
  ];

  const gifArr = [
    {url:"https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif", title:"Happy"},
    {url:"https://media.giphy.com/media/l0MYt5jPR6QX5pnqM/giphy.gif", title:"Laugh"},
    {url:"https://media.giphy.com/media/13ZHjidRzoi7n2/giphy.gif", title:"Cute"},
    {url:"https://media.giphy.com/media/xT9IgIc0lryrxvqVGM/giphy.gif", title:"Party"},
    {url:"https://media.giphy.com/media/6brH8M9ZrAp0A/giphy.gif", title:"Dance"},
    {url:"https://media.giphy.com/media/l0HlSNOxJB956qwfK/giphy.gif", title:"Excited"},
    {url:"https://media.giphy.com/media/wWue0rCDOphOE/giphy.gif", title:"Thumbs Up"},
    {url:"https://media.giphy.com/media/3oriO0OEd9QIDdllqo/giphy.gif", title:"Applause"},
    {url:"https://media.giphy.com/media/26ufnwz3wDUli7GU0/giphy.gif", title:"Sad"},
    {url:"https://media.giphy.com/media/9Y5BbDSkSTiY8/giphy.gif", title:"Clapping"},
    {url:"https://media.giphy.com/media/l0Exk8EUzSLsrErEQ/giphy.gif", title:"Thinking"},
    {url:"https://media.giphy.com/media/l1J9EdzfOSgfyueLm/giphy.gif", title:"Love"},
    {url:"https://media.giphy.com/media/3o6ZsZKn6QY6kWcJcY/giphy.gif", title:"Cool"},
    {url:"https://media.giphy.com/media/KzJkzjggfGN5Py6zz6/giphy.gif", title:"Disappointed"},
    {url:"https://media.giphy.com/media/1BXa2alBjrCXC/giphy.gif", title:"Win"},
    {url:"https://media.giphy.com/media/MeJgB3yMMwIaHmKD4z/giphy.gif", title:"Cry"},
    {url:"https://media.giphy.com/media/3oEduSbSGpGaRX2Vri/giphy.gif", title:"Funny"},
    {url:"https://media.giphy.com/media/YTbZzCkRQCEJa/giphy.gif", title:"Facepalm"},
    {url:"https://media.giphy.com/media/12XDYvMJNcmLgQ/giphy.gif", title:"Wink"},
    {url:"https://media.giphy.com/media/fAnEC88L6HnRs/giphy.gif", title:"Wave"}
  ];

  const stickerArr = [
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/40/emoji-6245549_1280.png", title:"Smile"},
    {url:"https://cdn.pixabay.com/photo/2021/04/13/09/31/emoji-6172546_1280.png", title:"Love"},
    {url:"https://cdn.pixabay.com/photo/2017/01/31/13/14/emoji-2025784_1280.png", title:"Cool"},
    {url:"https://cdn.pixabay.com/photo/2016/03/31/19/58/smile-1294766_1280.png", title:"Happy"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/41/emoji-6245553_1280.png", title:"Party"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/40/emoji-6245550_1280.png", title:"Blush"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/41/emoji-6245557_1280.png", title:"Kiss"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/41/emoji-6245558_1280.png", title:"Tongue Out"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/40/emoji-6245552_1280.png", title:"Sad"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/41/emoji-6245554_1280.png", title:"Wink"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/41/emoji-6245555_1280.png", title:"Angry"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/41/emoji-6245556_1280.png", title:"Surprised"},
    {url:"https://cdn.pixabay.com/photo/2021/05/11/06/40/emoji-6245551_1280.png", title:"Confused"},
    {url:"https://cdn.pixabay.com/photo/2016/03/31/19/58/smile-1294766_1280.png", title:"Joy"}
  ];

  // --- Functions for rendering and inserting ---
  function renderEmojiList(filter='') {
    const list = document.getElementById('tab-emoji');
    const label = document.getElementById('emojiLabel');
    list.innerHTML = '';
    let results = emojiData;
    if(filter.trim().length) {
      const f = filter.toLowerCase();
      results = emojiData.filter(e =>
        e.name.toLowerCase().includes(f) ||
        e.keywords.toLowerCase().includes(f) ||
        e.emoji.includes(f)
      );
    }
    results.slice(0, 400).forEach(e => {
      const btn = document.createElement('button');
      btn.className = "emoji-btn";
      btn.textContent = e.emoji;
      btn.title = e.name;
      btn.type = 'button';
      btn.onclick = () => insertToTextarea(e.emoji);
      list.appendChild(btn);
    });
    label.style.marginTop = "16px";
    label.textContent = results.length ?
      `Menampilkan ${Math.min(results.length, 400)} emoji${filter ? ' hasil pencarian ('+results.length+' ditemukan)' : ''}` :
      'Tidak ada emoji ditemukan';
    document.getElementById('emojiError').style.display = results.length ? 'none' : 'block';
  }

  function renderKaomojiList(filter='') {
    const list = document.getElementById('tab-kaomoji');
    const label = document.getElementById('emojiLabel');
    list.innerHTML = '';
    let filtered = kaomojiArr;
    if (filter.trim().length) {
      const f = filter.toLowerCase();
      filtered = kaomojiArr.filter(k => k.toLowerCase().includes(f));
    }
    filtered.forEach(k => {
      const btn = document.createElement('button');
      btn.className = "kaomoji-btn";
      btn.textContent = k;
      btn.title = k;
      btn.type = 'button';
      btn.onclick = () => insertToTextarea(k);
      list.appendChild(btn);
    });
    label.style.marginTop = "16px";
    label.textContent = filtered.length ?
      `Menampilkan ${filtered.length} kaomoji${filter ? ' hasil pencarian ('+filtered.length+' ditemukan)' : ''}` :
      'Tidak ada kaomoji ditemukan';
    document.getElementById('emojiError').style.display = filtered.length ? 'none' : 'block';
  }

  function renderGifList(filter='') {
    const list = document.getElementById('tab-gif');
    const label = document.getElementById('emojiLabel');
    list.innerHTML = '';
    let filtered = gifArr;
    if (filter.trim().length) {
      const f = filter.toLowerCase();
      filtered = gifArr.filter(gif =>
        (gif.title && gif.title.toLowerCase().includes(f))
      );
    }
    filtered.forEach(gif => {
      const btn = document.createElement('button');
      btn.className = "gif-btn";
      btn.type = 'button';
      btn.innerHTML = `<img src="${gif.url}" alt="${gif.title}">`;
      btn.title = gif.title;
      btn.onclick = () => insertToTextarea(`[GIF: ${gif.title}]`);
      list.appendChild(btn);
    });
    label.textContent = '';
    document.getElementById('emojiError').style.display = filtered.length ? 'none' : 'block';
  }

  function renderStickerList(filter='') {
    const list = document.getElementById('tab-sticker');
    const label = document.getElementById('emojiLabel');
    list.innerHTML = '';
    let filtered = stickerArr;
    if (filter.trim().length) {
      const f = filter.toLowerCase();
      filtered = stickerArr.filter(st =>
        (st.title && st.title.toLowerCase().includes(f))
      );
    }
    filtered.forEach(st => {
      const btn = document.createElement('button');
      btn.className = "sticker-btn";
      btn.type = 'button';
      btn.innerHTML = `<img src="${st.url}" alt="${st.title}">`;
      btn.title = st.title;
      btn.onclick = () => insertToTextarea(`[Sticker: ${st.title}]`);
      list.appendChild(btn);
    });
    label.textContent = '';
    document.getElementById('emojiError').style.display = filtered.length ? 'none' : 'block';
  }

  function insertToTextarea(str) {
    const textarea = document.getElementById('message');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const val = textarea.value;
    textarea.value = val.slice(0, start) + str + val.slice(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + str.length;
  }

  const emojiBtn = document.getElementById('emojiToggleBtn');
  const emojiPopup = document.getElementById('emojiPopup');
  const emojiSearch = document.getElementById('emojiSearch');

  emojiBtn.onclick = (e) => {
    e.preventDefault();
    emojiPopup.style.display = emojiPopup.style.display === 'block' ? 'none' : 'block';
    if(emojiPopup.style.display === 'block') {
      const activeTab = document.querySelector('.emoji-tab.active').dataset.tab;
      renderAllTabs(activeTab, emojiSearch.value.trim());
    }
  };

  document.addEventListener('click', function(e){
    if(!emojiPopup.contains(e.target) && e.target !== emojiBtn){
      emojiPopup.style.display = 'none';
    }
  });

  document.querySelectorAll('.emoji-tab').forEach(tab => {
    tab.addEventListener('click', function(){
      document.querySelectorAll('.emoji-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      document.getElementById('tab-emoji').style.display = tab.dataset.tab === 'emoji' ? 'flex' : 'none';
      document.getElementById('tab-kaomoji').style.display = tab.dataset.tab === 'kaomoji' ? 'flex' : 'none';
      document.getElementById('tab-gif').style.display = tab.dataset.tab === 'gif' ? 'flex' : 'none';
      document.getElementById('tab-sticker').style.display = tab.dataset.tab === 'sticker' ? 'flex' : 'none';
      renderAllTabs(tab.dataset.tab, emojiSearch.value.trim());
    });
  });

  emojiSearch.addEventListener('input', function(){
    const val = this.value.trim();
    const activeTab = document.querySelector('.emoji-tab.active').dataset.tab;
    renderAllTabs(activeTab, val);
  });

  function renderAllTabs(tab, filter) {
    if(tab === 'emoji'){
      renderEmojiList(filter);
    } else if(tab === 'kaomoji'){
      renderKaomojiList(filter);
    } else if(tab === 'gif'){
      renderGifList(filter);
    } else if(tab === 'sticker'){
      renderStickerList(filter);
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    renderEmojiList();
    renderKaomojiList();
    renderGifList();
    renderStickerList();
  });
</script>
@endsection
