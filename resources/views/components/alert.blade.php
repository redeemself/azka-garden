{{-- resources/views/components/alert.blade.php --}}
@props([
  'type' => 'info',   // jenis: 'success','warning','error','info'
  'dismissible' => false
])

@php
  $colors = [
    'info'    => 'bg-blue-100 text-blue-800',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'error'   => 'bg-red-100 text-red-800',
  ];
  $classes = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => "p-4 rounded-md flex items-start space-x-3 $classes"]) }}>
  <div class="flex-1">
    {{ $slot }}
  </div>

  @if($dismissible)
    <button type="button" 
            class="text-xl font-bold leading-none focus:outline-none"
            @click="this.closest('div').remove()"
            aria-label="Close">
      &times;
    </button>
  @endif
</div>
