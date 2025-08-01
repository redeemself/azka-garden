@extends('layouts.app')

@section('title', 'Pembayaran - Azka Garden')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>
                        <i class="fas fa-credit-card me-2"></i>
                        Riwayat Pembayaran
                    </h2>
                    <a href="{{ route('cart.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Kembali ke Keranjang
                    </a>
                </div>

                <!-- Payment Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="text-primary">{{ $paymentStats['total_payments'] }}</h3>
                                <p class="mb-0">Total Pembayaran</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="text-success">{{ $paymentStats['completed_payments'] }}</h3>
                                <p class="mb-0">Selesai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="text-warning">{{ $paymentStats['pending_payments'] }}</h3>
                                <p class="mb-0">Menunggu</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <h3 class="text-info">Rp{{ number_format($paymentStats['total_amount'], 0, ',', '.') }}</h3>
                                <p class="mb-0">Total Dibayar</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        @if ($payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode Pembayaran</th>
                                            <th>Nomor Pesanan</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td>{{ $payment->payment_code }}</td>
                                                <td>{{ $payment->order->order_number }}</td>
                                                <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                <td>
                                                    @switch($payment->status)
                                                        @case('completed')
                                                            <span class="badge bg-success">Selesai</span>
                                                        @break

                                                        @case('pending')
                                                            <span class="badge bg-warning">Menunggu</span>
                                                        @break

                                                        @case('cancelled')
                                                            <span class="badge bg-danger">Dibatalkan</span>
                                                        @break

                                                        @default
                                                            <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('user.payment.show', $payment) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $payments->links() }}
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                <h5>Belum Ada Pembayaran</h5>
                                <p class="text-muted">Anda belum melakukan pembayaran apapun</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Mulai Belanja
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
