<header
  x-data="{ scrolled: false, drawerOpen: false }"
  @scroll.window="scrolled = window.pageYOffset > 50"
  :class="scrolled ? 'bg-white/90 shadow-md backdrop-blur-md' : 'bg-white'"
  class="fixed top-0 left-0 right-0 z-[110] w-full transition-colors duration-500"
  style="height: 72px;"
>
  <div class="flex items-center justify-between w-full px-8 py-4 min-h-[72px]">
    <!-- Logo di kiri -->
    <a href="{{ route('home') }}" class="flex items-center space-x-2 shrink-0">
      <svg xmlns="http://www.w3.org/2000/svg"
           class="w-8 h-8 text-green-500"
           fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z" />
      </svg>
      <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-green-600">
        Azka Garden
      </span>
    </a>

    <!-- Navbar menu utama (tengah) -->
    <nav class="items-center justify-center flex-1 hidden mx-8 space-x-6 lg:flex">
      <a href="{{ route('home') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('home') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Beranda
      </a>
      <a href="{{ route('about') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('about') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Tentang
      </a>
      <a href="{{ route('products.index') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('products.index') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Produk
      </a>
      <a href="{{ route('services.index') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('services.index') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Layanan
      </a>
      <a href="{{ route('blog.index') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('blog.index') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Blog
      </a>
      <a href="{{ route('artikel.index') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('artikel.index') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Artikel
      </a>
      <a href="{{ route('contact') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('contact') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Kontak
      </a>
      <a href="{{ route('faq') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('faq') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        FAQ
      </a>
      <a href="{{ route('sitemap.html') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('sitemap.html') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
        Sitemap
      </a>
      <a href="{{ route('membership.index') }}"
         class="text-gray-700 hover:text-green-600 transition
         {{ request()->routeIs('membership.index') ? 'font-semibold underline underline-offset-4 decoration-green-400' : '' }}">
         Langganan
      </a>
    </nav>

    <!-- Menu kanan desktop -->
    <div class="items-center hidden space-x-3 lg:flex">
      @guest('web')
      <!-- Tombol Masuk -->
      <div class="relative" x-data="{ openMasuk: false }" @click.outside="openMasuk = false" x-cloak>
        <button
          @click="openMasuk = !openMasuk"
          class="flex items-center px-3 py-2 space-x-2 font-semibold text-green-700 transition border border-green-400 rounded-lg bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-300"
          tabindex="0"
          aria-haspopup="true"
          :aria-expanded="openMasuk.toString()"
        >
          <span>Masuk</span>
          <svg :class="openMasuk ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M6 9l6 6 6-6"/>
          </svg>
        </button>
        <div
          x-show="openMasuk"
          x-transition
          class="absolute right-0 z-50 mt-2 bg-white rounded-md shadow-lg w-44 ring-1 ring-black ring-opacity-5 focus:outline-none"
          role="menu"
          aria-label="Masuk dropdown"
        >
          <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-green-700 transition rounded hover:bg-green-100" role="menuitem">Pengguna</a>
          <a href="{{ route('admin.login') }}" class="block px-4 py-2 text-sm text-green-700 transition rounded hover:bg-green-100" role="menuitem">Admin</a>
        </div>
      </div>
      <!-- Tombol Registrasi -->
      <div class="relative" x-data="{ openReg: false }" @click.outside="openReg = false" x-cloak>
        <button
          @click="openReg = !openReg"
          class="flex items-center px-3 py-2 space-x-2 font-semibold text-green-700 transition border border-green-400 rounded-lg bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-300"
          tabindex="0"
          aria-haspopup="true"
          :aria-expanded="openReg.toString()"
        >
          <span>Registrasi</span>
          <svg :class="openReg ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M6 9l6 6 6-6"/>
          </svg>
        </button>
        <div
          x-show="openReg"
          x-transition
          class="absolute right-0 z-50 mt-2 bg-white rounded-md shadow-lg w-44 ring-1 ring-black ring-opacity-5 focus:outline-none"
          role="menu"
          aria-label="Registrasi dropdown"
        >
          <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-green-700 transition rounded hover:bg-green-100" role="menuitem">Pengguna</a>
          <a href="{{ route('admin.register') }}" class="block px-4 py-2 text-sm text-green-700 transition rounded hover:bg-green-100" role="menuitem">Admin</a>
        </div>
      </div>
      @endguest

      @auth('web')
          @php
              $user = auth()->user();
              $createdDate = ($user && $user->getAttribute('created_at'))
                  ? \Carbon\Carbon::parse($user->getAttribute('created_at'))->format('d M Y')
                  : '-';
              $roles = optional($user->roles)->pluck('name')->implode(', ') ?? '-';
              $avatar = $user->profile_photo_path
                  ? asset('storage/' . $user->profile_photo_path)
                  : (optional($user->avatar)->url ?? asset('images/default-user.png'));
          @endphp
          <div class="relative" x-data="{ open: false }" @click.outside="open = false" x-cloak>
              <button
                  @click="open = !open"
                  class="flex items-center px-3 py-2 font-semibold text-gray-700 bg-white border border-green-400 rounded-lg hover:bg-green-50 hover:text-green-700 focus:outline-none"
                  tabindex="0"
                  aria-haspopup="true"
                  :aria-expanded="open.toString()"
              >
                  <img src="{{ $avatar }}"
                      alt="Avatar" class="object-cover mr-2 rounded-full w-7 h-7" />
                  {{ $user->name }}
                  <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 ml-2 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
                      stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                      <path d="M6 9l6 6 6-6"></path>
                  </svg>
              </button>
              <div
                  x-show="open"
                  x-transition
                  class="absolute right-0 z-50 w-64 mt-2 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  role="menu"
                  aria-label="User menu"
              >
                  <div class="flex items-center px-4 py-3 border-b">
                      <img src="{{ $avatar }}"
                          alt="Avatar" class="object-cover w-10 h-10 mr-3 rounded-full" />
                      <div>
                          <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                          <div class="text-xs text-gray-600">{{ $user->email }}</div>
                          <div class="text-xs font-semibold text-green-600">
                              {{ $roles }}
                          </div>
                          <div class="mt-1 text-xs text-gray-500">
                              Tgl Daftar: {{ $createdDate }}
                          </div>
                      </div>
                  </div>
                  <a href="{{ route('user.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Profil
                  </a>
                  <a href="{{ route('user.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Pesanan Saya
                  </a>
                  <a href="{{ route('user.orders.history.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Riwayat Pesanan
                  </a>
                  <a href="{{ route('user.cart.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Keranjang
                  </a>
                  <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-red-100" role="menuitem">
                          Logout
                      </button>
                  </form>
              </div>
          </div>
      @endauth
    </div>

    <!-- Hamburger versi mobile -->
    <button
      @click="drawerOpen = !drawerOpen"
      class="z-10 flex items-center ml-2 text-green-600 lg:hidden focus:outline-none"
      aria-label="Open main menu"
      style="position: absolute; right: 1.5rem; top: 1rem; z-index: 111;"
    >
      <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        <path x-show="!drawerOpen" d="M4 6h16M4 12h16M4 18h16"></path>
        <path x-show="drawerOpen" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
  </div>

  <!-- Overlay for drawer mobile -->
  <template x-if="drawerOpen">
    <div class="fixed inset-0 z-[9998] lg:hidden">
      <div
        class="absolute inset-0 transition-opacity duration-300 pointer-events-auto bg-black/40 backdrop-blur-lg"
        @click="drawerOpen = false"
      ></div>
    </div>
  </template>

  <!-- Drawer mobile -->
  <nav
    :class="drawerOpen ? 'translate-x-0' : 'translate-x-full'"
    class="fixed top-0 right-0 bottom-0 h-full w-80 bg-white shadow-xl transition-transform duration-300 z-[9999] lg:hidden overflow-y-auto"
    x-show="drawerOpen"
    style="background-color: #fff;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
  >
    <div class="flex items-center pt-3 px-6 pb-2 border-b bg-white min-h-[72px] w-full justify-between">
      <a href="{{ route('home') }}" class="flex items-center space-x-2 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-8 h-8 text-green-500"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 2C8 5 4 8 4 12s4 7 8 7 8-4 8-7-4-7-8-7z" />
        </svg>
        <span class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-green-600">
          Azka Garden
        </span>
      </a>
      <button
        @click="drawerOpen = false"
        class="ml-auto text-gray-400 hover:text-green-600"
        aria-label="Close main menu"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
          <path d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    <!-- Drawer content -->
    <div class="flex flex-col min-h-0 px-6 pt-4 space-y-4 overflow-y-auto grow" style="height:calc(100dvh - 72px);">
      <div x-data="{ open: true }">
        <button @click="open = !open"
          class="flex items-center justify-between w-full px-2 py-2 font-bold text-green-700 transition rounded bg-green-50 hover:bg-green-100"
          aria-haspopup="true"
          :aria-expanded="open.toString()"
        >
          <span>Beranda</span>
          <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 ml-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M6 9l6 6 6-6"/>
          </svg>
        </button>
        <div x-show="open" x-transition class="pl-4 space-y-1 font-normal">
          <a href="{{ route('about') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('about') ? 'underline decoration-green-400' : '' }}">Tentang Kami</a>
          <a href="{{ route('products.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('products.index') ? 'underline decoration-green-400' : '' }}">Produk</a>
          <a href="{{ route('services.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('services.index') ? 'underline decoration-green-400' : '' }}">Layanan</a>
          <a href="{{ route('blog.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('blog.index') ? 'underline decoration-green-400' : '' }}">Blog</a>
          <a href="{{ route('artikel.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('artikel.index') ? 'underline decoration-green-400' : '' }}">Artikel</a>
          <a href="{{ route('contact') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('contact') ? 'underline decoration-green-400' : '' }}">Kontak</a>
          <a href="{{ route('faq') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('faq') ? 'underline decoration-green-400' : '' }}">FAQ</a>
          <a href="{{ route('sitemap.html') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('sitemap.html') ? 'underline decoration-green-400' : '' }}">Sitemap</a>
          <a href="{{ route('membership.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('membership.index') ? 'underline decoration-green-400' : '' }}">Membership</a>
          @auth('web')
            <a href="{{ route('user.profile.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('user.profile.index') ? 'underline decoration-green-400' : '' }}">Profil Saya</a>
            <a href="{{ route('user.orders.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('user.orders.index') ? 'underline decoration-green-400' : '' }}">Pesanan Saya</a>
            <a href="{{ route('user.orders.history.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('user.orders.history.index') ? 'underline decoration-green-400' : '' }}">Riwayat Pesanan</a>
            <a href="{{ route('user.cart.index') }}" class="block py-1 text-gray-700 hover:text-green-600 {{ request()->routeIs('user.cart.index') ? 'underline decoration-green-400' : '' }}">Keranjang</a>
          @endauth
        </div>
      </div>

      <hr class="my-2 border-green-100">

      <!-- Masuk & Register untuk mobile -->
      <div class="space-y-2">
        <div x-data="{ masukOpen: false }">
          <button
            @click="masukOpen = !masukOpen"
            class="flex items-center justify-between w-full px-3 py-2 font-bold text-green-700 transition rounded-lg bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-300"
            aria-haspopup="true"
            :aria-expanded="masukOpen.toString()"
          >
            <span>Masuk</span>
            <svg :class="masukOpen ? 'rotate-180' : ''" class="w-5 h-5 ml-2 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M6 9l6 6 6-6"/>
            </svg>
          </button>
          <div x-show="masukOpen" x-transition class="pl-4 mt-1 space-y-1">
            <a href="{{ route('login') }}" class="block px-2 py-2 text-green-700 transition rounded hover:underline hover:bg-green-100">Masuk Pengguna</a>
            <a href="{{ route('admin.login') }}" class="block px-2 py-2 text-green-700 transition rounded hover:underline hover:bg-green-100">Masuk Admin</a>
          </div>
        </div>
        <div x-data="{ regOpen: false }">
          <button
            @click="regOpen = !regOpen"
            class="flex items-center justify-between w-full px-3 py-2 font-bold text-green-700 transition rounded-lg bg-green-50 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-300"
            aria-haspopup="true"
            :aria-expanded="regOpen.toString()"
          >
            <span>Registrasi</span>
            <svg :class="regOpen ? 'rotate-180' : ''" class="w-5 h-5 ml-2 transition-transform" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <path d="M6 9l6 6 6-6"/>
            </svg>
          </button>
          <div x-show="regOpen" x-transition class="pl-4 mt-1 space-y-1">
            <a href="{{ route('register') }}" class="block px-2 py-2 text-green-700 transition rounded hover:underline hover:bg-green-100">Registrasi Pengguna</a>
            <a href="{{ route('admin.register') }}" class="block px-2 py-2 text-green-700 transition rounded hover:underline hover:bg-green-100">Registrasi Admin</a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>
