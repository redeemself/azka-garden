@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container px-4 py-6 mx-auto">
        <h1 class="mb-4 text-3xl font-bold">Keranjang Belanja</h1>

        @if ($cartItems->isEmpty())
            <p class="text-gray-600">Keranjang Anda kosong. <a href="{{ route('products.index') }}"
                    class="text-green-600 underline">Mulai belanja</a>.</p>
        @else
            <div class="mb-6 overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 text-left">Produk</th>
                            <th class="p-2 text-center">Qty</th>
                            <th class="p-2 text-right">Harga</th>
                            <th class="p-2 text-right">Subtotal</th>
                            <th class="p-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-t" id="cart-item-{{ $item->id }}">
                                <td class="flex items-center gap-2 p-2">
                                    @if ($item->product && $item->product->image_url)
                                        <img src="{{ asset('storage/' . $item->product->image_url) }}"
                                            class="object-cover w-12 h-12 rounded" alt="{{ $item->product->name }}">
                                    @endif
                                    <span>{{ $item->product->name ?? $item->name }}</span>
                                </td>
                                <td class="p-2 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button type="button"
                                            class="px-2 py-1 text-white bg-red-500 rounded-md decrement-btn hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                                            data-item-id="{{ $item->id }}"
                                            data-url="{{ route('cart.update', $item->id) }}"
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                            {{ $item->quantity <= 1 ? 'style=opacity:0.5;cursor:not-allowed' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </button>

                                        <span
                                            class="px-2 py-1 bg-white border rounded-md quantity-display">{{ $item->quantity }}</span>

                                        <button type="button"
                                            class="px-2 py-1 text-white bg-green-500 rounded-md increment-btn hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500"
                                            data-item-id="{{ $item->id }}"
                                            data-url="{{ route('cart.update', $item->id) }}"
                                            {{ $item->product && $item->product->stock <= $item->quantity ? 'disabled' : '' }}
                                            {{ $item->product && $item->product->stock <= $item->quantity ? 'style=opacity:0.5;cursor:not-allowed' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="p-2 text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="p-2 text-right">
                                    Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                <td class="p-2 text-center">
                                    <button type="button"
                                        class="p-1 text-white bg-red-600 rounded remove-btn hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        data-item-id="{{ $item->id }}"
                                        data-url="{{ route('cart.remove', $item->id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t bg-gray-50">
                            <td colspan="3" class="p-2 font-semibold text-right">Total</td>
                            <td class="p-2 font-bold text-right">
                                Rp{{ number_format($cartItems->sum(function ($item) {return $item->price * $item->quantity;}),0,',','.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <form action="{{ route('checkout.index') }}" method="GET" class="space-y-4">
                <div>
                    <label for="shipping_method" class="block mb-1 font-medium">Metode Pengiriman</label>
                    <select name="shipping_method" id="shipping_method" class="w-full p-2 border rounded">
                        @foreach ($shippingOptions as $opt)
                            <option value="{{ $opt['id'] }}">
                                {{ $opt['name'] }} &mdash; Rp{{ number_format($opt['price'], 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_method" class="block mb-1 font-medium">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="w-full p-2 border rounded">
                        @foreach ($paymentMethods as $pm)
                            <option value="{{ $pm->code }}">
                                {{ $pm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center px-4 py-2 text-white bg-gray-600 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Lanjut Belanja
                    </a>

                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 text-white transition bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Lanjut ke Checkout
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Toast Notification -->
            <div id="toast"
                class="fixed z-50 flex items-center w-auto max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded-lg shadow right-5 bottom-5 dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800"
                style="display: none;">
                <div
                    class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="pl-4 text-sm font-normal" id="toast-message"></div>
            </div>
        @endif
    </div>

    @if (!$cartItems->isEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const token = "{{ csrf_token() }}";
                const toast = document.getElementById('toast');
                const toastMessage = document.getElementById('toast-message');
                const cartCounterElement = document.getElementById('cart-counter'); // Cart counter in header/navbar

                // Show toast notification
                function showToast(message, duration = 3000) {
                    toastMessage.textContent = message;
                    toast.style.display = 'flex';
                    setTimeout(() => {
                        toast.style.display = 'none';
                    }, duration);
                }

                // Update cart counter in header/navbar
                function updateCartCounter(count) {
                    if (cartCounterElement) {
                        cartCounterElement.textContent = count;
                    }
                }

                // Increment quantity
                document.querySelectorAll('.increment-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const itemId = this.dataset.itemId;
                        const url = this.dataset.url;
                        const quantityDisplay = this.closest('tr').querySelector('.quantity-display');
                        const decrementBtn = this.closest('tr').querySelector('.decrement-btn');

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    action: 'increment'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update quantity display
                                    quantityDisplay.textContent = data.quantity;

                                    // Update subtotal
                                    const subtotalCell = this.closest('tr').querySelector(
                                        'td:nth-child(4)');
                                    subtotalCell.textContent = 'Rp' + data.subtotal_formatted;

                                    // Update footer total
                                    const footerTotal = document.querySelector(
                                        'tfoot td:nth-child(2)');
                                    if (footerTotal) {
                                        footerTotal.textContent = 'Rp' + data.cart_total_formatted;
                                    }

                                    // Enable decrement button if quantity > 1
                                    if (data.quantity > 1) {
                                        decrementBtn.disabled = false;
                                        decrementBtn.style.opacity = '1';
                                        decrementBtn.style.cursor = 'pointer';
                                    }

                                    // Disable increment button if reached stock limit
                                    if (data.stock_limit_reached) {
                                        this.disabled = true;
                                        this.style.opacity = '0.5';
                                        this.style.cursor = 'not-allowed';
                                    }

                                    // Update cart counter
                                    updateCartCounter(data.cart_count);

                                    showToast('Jumlah produk berhasil ditambah');
                                } else {
                                    showToast(data.message || 'Gagal menambah jumlah produk');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('Terjadi kesalahan saat memperbarui keranjang');
                            });
                    });
                });

                // Decrement quantity
                document.querySelectorAll('.decrement-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const itemId = this.dataset.itemId;
                        const url = this.dataset.url;
                        const quantityDisplay = this.closest('tr').querySelector('.quantity-display');
                        const incrementBtn = this.closest('tr').querySelector('.increment-btn');

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    action: 'decrement'
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Update quantity display
                                    quantityDisplay.textContent = data.quantity;

                                    // Update subtotal
                                    const subtotalCell = this.closest('tr').querySelector(
                                        'td:nth-child(4)');
                                    subtotalCell.textContent = 'Rp' + data.subtotal_formatted;

                                    // Update footer total
                                    const footerTotal = document.querySelector(
                                        'tfoot td:nth-child(2)');
                                    if (footerTotal) {
                                        footerTotal.textContent = 'Rp' + data.cart_total_formatted;
                                    }

                                    // Disable decrement button if quantity reaches 1
                                    if (data.quantity <= 1) {
                                        this.disabled = true;
                                        this.style.opacity = '0.5';
                                        this.style.cursor = 'not-allowed';
                                    }

                                    // Enable increment button
                                    incrementBtn.disabled = false;
                                    incrementBtn.style.opacity = '1';
                                    incrementBtn.style.cursor = 'pointer';

                                    // Update cart counter
                                    updateCartCounter(data.cart_count);

                                    showToast('Jumlah produk berhasil dikurangi');
                                } else {
                                    showToast(data.message || 'Gagal mengurangi jumlah produk');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('Terjadi kesalahan saat memperbarui keranjang');
                            });
                    });
                });

                // Remove item
                document.querySelectorAll('.remove-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                            const itemId = this.dataset.itemId;
                            const url = this.dataset.url;
                            const tableRow = this.closest('tr');

                            fetch(url, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Remove the row from the table
                                        tableRow.remove();

                                        // Update footer total
                                        const footerTotal = document.querySelector(
                                            'tfoot td:nth-child(2)');
                                        if (footerTotal) {
                                            footerTotal.textContent = 'Rp' + data
                                                .cart_total_formatted;
                                        }

                                        // Update cart counter
                                        updateCartCounter(data.cart_count);

                                        showToast('Produk berhasil dihapus dari keranjang');

                                        // Reload page if cart is empty
                                        if (data.cart_count === 0) {
                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 1500);
                                        }
                                    } else {
                                        showToast(data.message ||
                                            'Gagal menghapus produk dari keranjang');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    showToast('Terjadi kesalahan saat menghapus dari keranjang');
                                });
                        }
                    });
                });
            });
        </script>
    @endif
@endsection
