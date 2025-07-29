{{-- resources/views/components/input.blade.php --}}
@props([
  'type'  => 'text',
  'name',
  'value' => old($name),
  'label' => null,
])

<div class="mb-4">
  @if($label)
    <label for="{{ $name }}" class="block mb-1 font-medium text-gray-700">
      {{ $label }}
    </label>
  @endif

  <input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ $value }}"
    {{ $attributes->merge([
      'class' => 'w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500'
        . ($errors->has($name) ? ' border-red-500' : ''),
    ]) }}
  />

  @error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
  @enderror
</div>
