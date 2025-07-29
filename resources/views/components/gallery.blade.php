<div>
    <h3 class="flex items-center gap-2 mb-4 text-xl font-bold text-green-700 md:text-2xl">
        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="5" width="18" height="14" rx="3" />
            <circle cx="8.5" cy="12" r="2.5" />
            <path d="M21 19l-5.5-5.5a2 2 0 00-2.8 0L3 19"/>
        </svg>
        Galeri Suasana Azka Garden
    </h3>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        @foreach($images as $i => $img)
            <figure class="relative mb-4 overflow-hidden transition bg-white border border-green-100 rounded-lg shadow hover:shadow-lg">
                <img src="{{ asset($img) }}" alt="Azka Garden {{ $i+1 }}" class="object-cover w-full h-48 sm:h-56 md:h-64" loading="lazy">
                <figcaption class="absolute bottom-0 left-0 flex items-center w-full gap-1 px-3 py-1 text-xs text-white sm:text-sm bg-gradient-to-t from-black/70 to-transparent">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                    Azka Garden #{{ $i+1 }}
                </figcaption>
            </figure>
        @endforeach
    </div>
</div>
