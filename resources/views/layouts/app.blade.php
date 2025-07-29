<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
 <!-- CSRF Token - Wajib untuk keamanan permintaan AJAX -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Azka Garden')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
  <style>
    html, body {
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      height: 100%;
      min-height: 100%;
    }
    #global-loader {
      transition: opacity 0.5s;
      opacity: 1;
      background: rgba(255,255,255,0.96);
    }
    #global-loader[hidden] {
      opacity: 0;
      pointer-events: none;
      display: none;
    }
    .loader-spinner {
      border: 8px solid #e5e7eb;
      border-top: 8px solid #22c55e;
      border-radius: 50%;
      width: 70px;
      height: 70px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin {
      0% { transform: rotate(0deg);}
      100% { transform: rotate(360deg);}
    }
    .loader-text {
      margin-top: 1.5rem;
      font-size: 1.25rem;
      color: #22c55e;
      font-weight: bold;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px #e5e7eb;
    }
    /* ==== NAVBAR HEIGHT FIX ==== */
    /* Adjust this to match your navbar's actual height */
    .navbar-fixed { position: fixed; top: 0; left: 0; right: 0; z-index: 40; }
    .main-content-offset { padding-top: 80px; }
    @media (min-width: 768px) {
      .main-content-offset { padding-top: 96px; }
    }
    @media (min-width: 1024px) {
      .main-content-offset { padding-top: 112px; }
    }
  </style>
</head>
<body class="relative flex flex-col min-h-screen overflow-x-hidden">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  {{-- Loader --}}
  <div id="global-loader" class="fixed inset-0 z-50 flex flex-col items-center justify-center">
    <div class="loader-spinner"></div>
    <div class="loader-text">Azka Garden Memuat Data...</div>
  </div>

  {{-- Navbar utama --}}
  <div class="navbar-fixed">
    @include('partials.navbar')
  </div>

  {{-- Konten utama --}}
  <main class="flex-1 main-content-offset">
    @yield('content')
  </main>

  {{-- Footer --}}
  @include('partials.footer')

  @stack('scripts')

</body>
</html>
