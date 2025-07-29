<!-- Modern Confirm Modal Component -->
<div id="{{ $id ?? 'confirmModal' }}" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/30 transition-all duration-300 opacity-0 pointer-events-none">
  <div class="bg-white rounded-2xl shadow-2xl w-[95vw] max-w-xs md:max-w-md p-6 relative scale-95 transition-all duration-300">
    <div class="flex items-center gap-3 mb-3">
      <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" class="fill-yellow-50"></circle>
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
      </svg>
      <span class="text-lg font-semibold text-yellow-700">{{ $title ?? 'Konfirmasi' }}</span>
    </div>
    <div class="text-gray-800 mb-5 text-base leading-relaxed">
      {!! $message ?? 'Apakah Anda yakin?' !!}
    </div>
    <div class="flex justify-end gap-2 mt-2">
      <button type="button" class="modal-cancel px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition">Batal</button>
      <button type="button" class="modal-ok px-4 py-2 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-bold shadow transition">OK</button>
    </div>
    <button type="button" class="absolute top-2 right-3 text-gray-400 hover:text-gray-700" onclick="closeConfirmModal()">
      <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</div>
