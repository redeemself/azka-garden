@extends('layouts.app')

@section('title', 'Alamat Saya')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<style>
:root {
    --primary: #166534;
    --primary-light: #16a34a;
    --primary-dark: #14532d;
    --primary-bg: #f0fdf4;
    --primary-bg-hover: #dcfce7;
    --accent: #4ade80;
    --white: #ffffff;
    --black: #111827;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --error: #b91c1c;
    --error-bg: #fee2e2;
    --warning: #ea580c;
    --warning-bg: #ffedd5;
    --success: #16a34a;
    --success-bg: #dcfce7;
    --info: #0284c7;
    --info-bg: #e0f2fe;
}

.address-container {
    max-width: 1200px;
    margin: 2rem auto 3rem;
    padding: 0 1rem;
}

.address-header {
    text-align: center;
    margin-bottom: 2rem;
}

.address-header h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
}

.address-header p {
    color: var(--gray-600);
    font-size: 1.1rem;
}

.address-actions {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
}

.add-address-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--primary);
    color: var(--white);
    padding: 0.75rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.add-address-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    color: var(--white);
}

.address-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.address-card {
    background: var(--white);
    border-radius: 1rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid var(--gray-200);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.address-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.address-card.primary {
    border-color: var(--primary-light);
}

.address-card.primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: var(--primary-light);
}

.address-card-header {
    background: var(--primary-bg);
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.address-card.primary .address-card-header {
    background: var(--primary-bg-hover);
}

.address-card-title {
    color: var(--primary-dark);
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.primary-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    background: var(--primary);
    color: var(--white);
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.75rem;
    margin-left: 0.5rem;
}

.address-card-body {
    padding: 1.5rem;
    flex: 1;
}

.address-detail-row {
    margin-bottom: 0.75rem;
    display: flex;
}

.address-detail-row:last-child {
    margin-bottom: 0;
}

.address-detail-label {
    flex: 0 0 100px;
    font-weight: 600;
    color: var(--gray-700);
}

.address-detail-value {
    flex: 1;
    color: var(--gray-800);
}

.address-coords {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px dashed var(--gray-200);
    font-size: 0.875rem;
    color: var(--gray-500);
}

.address-card-footer {
    padding: 1rem 1.5rem;
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    display: flex;
    gap: 0.75rem;
}

.address-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    border: 1px solid transparent;
    cursor: pointer;
}

.address-btn-edit {
    background: var(--gray-100);
    color: var(--gray-700);
    border-color: var(--gray-200);
}

.address-btn-edit:hover {
    background: var(--gray-200);
    color: var(--gray-800);
}

.address-btn-delete {
    background: var(--error-bg);
    color: var(--error);
    border-color: transparent;
}

.address-btn-delete:hover {
    background: var(--error);
    color: var(--white);
}

.address-btn-primary {
    background: var(--primary-bg);
    color: var(--primary);
    border-color: var(--primary-light);
}

.address-btn-primary:hover {
    background: var(--primary);
    color: var(--white);
}

.address-empty {
    text-align: center;
    padding: 3rem 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    animation: fadeIn 0.5s ease-out;
}

.address-empty-icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: center;
}

.address-empty-icon svg {
    width: 100px;
    height: 100px;
    stroke-width: 1;
}

.address-empty-title {
    font-size: 1.5rem;
    color: var(--gray-700);
    margin-bottom: 0.75rem;
}

.address-empty-text {
    color: var(--gray-500);
    margin-bottom: 1.5rem;
}

.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 350px;
    width: 100%;
    pointer-events: none;
}

.toast {
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
    padding: 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    transform: translateX(120%);
    opacity: 0;
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275),
                opacity 0.3s ease;
    pointer-events: auto;
    position: relative;
    overflow: hidden;
}

.toast::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 4px;
}

.toast.show {
    transform: translateX(0);
    opacity: 1;
}

.toast-success::before {
    background-color: var(--success);
}

.toast-error::before {
    background-color: var(--error);
}

.toast-warning::before {
    background-color: var(--warning);
}

.toast-info::before {
    background-color: var(--info);
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    transition: opacity 0.3s ease;
    visibility: hidden;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-dialog {
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    width: 100%;
    max-width: 450px;
    transform: scale(0.95);
    opacity: 0;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal-overlay.show .modal-dialog {
    transform: scale(1);
    opacity: 1;
}

@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@media (max-width: 768px) {
    .address-grid {
        grid-template-columns: 1fr;
    }

    .address-header h1 {
        font-size: 1.75rem;
    }

    .address-container {
        margin-top: 1rem;
        padding: 0 0.5rem;
    }

    .address-empty-icon svg {
        width: 80px;
        height: 80px;
    }
}
</style>

<div class="toast-container"></div>

<div class="address-container">
    <div class="address-header">
        <h1>Alamat Saya</h1>
        <p>Kelola alamat pengiriman Anda</p>
    </div>

    <div class="address-actions">
        <a href="{{ route('user.address.create') }}" class="add-address-btn">
            <i data-feather="plus"></i> Tambah Alamat Baru
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(count($addresses) > 0)
    <div class="address-grid">
        @foreach($addresses as $address)
        <div class="address-card {{ $address->is_primary ? 'primary' : '' }}" data-address-id="{{ $address->id }}">
            <div class="address-card-header">
                <h3 class="address-card-title">
                    <i data-feather="map-pin"></i>
                    {{ $address->label }}
                    @if($address->is_primary)
                    <span class="primary-badge">
                        <i data-feather="star" style="width: 14px; height: 14px;"></i>
                        Utama
                    </span>
                    @endif
                </h3>
            </div>

            <div class="address-card-body">
                <div class="address-detail-row">
                    <div class="address-detail-label">Penerima</div>
                    <div class="address-detail-value">{{ $address->recipient }}</div>
                </div>

                <div class="address-detail-row">
                    <div class="address-detail-label">Telepon</div>
                    <div class="address-detail-value">{{ $address->phone_number }}</div>
                </div>

                <div class="address-detail-row">
                    <div class="address-detail-label">Alamat</div>
                    <div class="address-detail-value">{{ $address->full_address }}</div>
                </div>

                <div class="address-detail-row">
                    <div class="address-detail-label">Kota</div>
                    <div class="address-detail-value">{{ $address->city }}</div>
                </div>

                <div class="address-detail-row">
                    <div class="address-detail-label">Kode Pos</div>
                    <div class="address-detail-value">{{ $address->zip_code }}</div>
                </div>

                @if($address->latitude && $address->longitude)
                <div class="address-coords">
                    <i data-feather="map" style="width: 14px; height: 14px; vertical-align: -2px;"></i>
                    Koordinat: {{ $address->latitude }}, {{ $address->longitude }}
                </div>
                @endif
            </div>

            <div class="address-card-footer">
                <a href="{{ route('user.address.edit', $address->id) }}" class="address-btn address-btn-edit">
                    <i data-feather="edit-2"></i> Edit
                </a>

                @if(!$address->is_primary)
                <form action="{{ route('user.addresses.setPrimary', $address->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="address-btn address-btn-primary w-100">
                        <i data-feather="star"></i> Set Utama
                    </button>
                </form>
                @endif

                <form action="{{ route('user.address.destroy', $address->id) }}" method="POST" class="flex-1 delete-address-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="address-btn address-btn-delete w-100">
                        <i data-feather="trash-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="address-empty">
        <div class="address-empty-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
        </div>
        <h2 class="address-empty-title">Belum Ada Alamat</h2>
        <p class="address-empty-text">Anda belum memiliki alamat tersimpan. Silakan tambahkan alamat baru.</p>
        <a href="{{ route('user.address.create') }}" class="add-address-btn">
            <i data-feather="plus"></i> Tambah Alamat Baru
        </a>
    </div>
    @endif
</div>

<script>
// Modern Toast Notification System
const toastSystem = {
    container: null,

    init() {
        // Create toast container if it doesn't exist
        this.container = document.querySelector('.toast-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    },

    show(type, title, message, duration = 3000) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        // Determine icon based on type
        const iconName = type === 'success' ? 'check-circle' :
                        type === 'error' ? 'alert-circle' :
                        type === 'warning' ? 'alert-triangle' : 'info';

        // Toast content
        toast.innerHTML = `
            <div class="toast-icon">
                <i data-feather="${iconName}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close">&times;</button>
        `;

        // Add to container
        this.container.appendChild(toast);

        // Initialize Feather icons
        if (window.feather) {
            feather.replace();
        }

        // Add event listener for close button
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.dismiss(toast);
        });

        // Show with animation
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });
        });

        // Auto dismiss after duration
        if (duration > 0) {
            setTimeout(() => {
                this.dismiss(toast);
            }, duration);
        }

        return toast;
    },

    dismiss(toast) {
        toast.classList.remove('show');

        toast.addEventListener('transitionend', () => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, { once: true });
    },

    success(title, message, duration) {
        return this.show('success', title, message, duration);
    },

    error(title, message, duration) {
        return this.show('error', title, message, duration);
    }
};

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize systems
    toastSystem.init();

    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    } else {
        // Load Feather Icons if not available
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js';
        script.onload = function() {
            feather.replace();
        };
        document.head.appendChild(script);
    }

    // Delete address confirmation
    document.querySelectorAll('.delete-address-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (confirm('Yakin ingin menghapus alamat ini?')) {
                this.submit();
            }
        });
    });

    // Show success message if present using toast system
    @if(session('success'))
        setTimeout(() => {
            toastSystem.success('Berhasil', '{{ session('success') }}');
        }, 300);
    @endif
});
</script>
@endsection
