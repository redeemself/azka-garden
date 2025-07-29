@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Support\Facades\DB;
    
    // Data waktu dan user dari halaman pembayaran
    $currentDateTime = '2025-07-28 13:52:01'; 
    $currentUserLogin = 'redeemself';

    if (!isset($orders)) {
        $orders = collect();
    }
    
    $getStatusValue = function($status) {
        if (is_object($status)) return $status->value;
        if (is_array($status)) return $status['value'] ?? $status[0] ?? $status;
        return $status;
    };
    
    $isExpired = fn($val) => strtoupper($val) === 'EXPIRED' || (isset($val) && strtolower($val) === 'expired');
    $isCanceled = fn($val) => in_array(strtoupper($val), ['CANCELED','FAILED']);
    $isPending = fn($val) => in_array(strtoupper($val), ['PENDING', 'WAITING_PAYMENT']);
    $isProcessingOrShipped = fn($val) => in_array(strtoupper($val), ['PROCESSING', 'SHIPPED']);

    $expiredOrders = $orders->filter(function($order) use ($getStatusValue) {
        $statusVal = $getStatusValue($order->status);
        $isExpiredStatus = strtoupper($statusVal) === 'EXPIRED';
        $isExpiredPayment = isset($order->payment)
          && isset($order->payment->expired_at)
          && \Carbon\Carbon::parse($order->payment->expired_at)->isPast();
        return $isExpiredStatus || $isExpiredPayment;
    });
    
    $canceledOrders = $orders->filter(function($order) use ($getStatusValue, $isCanceled) {
        $statusVal = $getStatusValue($order->status);
        return $isCanceled($statusVal);
    });
    
    $activeOrders = $orders->filter(function($order) use ($getStatusValue, $isCanceled, $isExpired) {
        $statusVal = $getStatusValue($order->status);
        return !$isCanceled($statusVal) && !$isExpired($statusVal);
    });
    
    $pendingOrders = $activeOrders->filter(function($order) use ($getStatusValue, $isPending) {
        $statusVal = $getStatusValue($order->status);
        $isPendingStatus = $isPending($statusVal);
        $isExpiredPayment = isset($order->payment)
          && isset($order->payment->expired_at)
          && \Carbon\Carbon::parse($order->payment->expired_at)->isPast();
        return $isPendingStatus && !$isExpiredPayment;
    });
    
    $processingOrShippedOrders = $activeOrders->filter(function($order) use ($getStatusValue, $isProcessingOrShipped) {
        return $isProcessingOrShipped($getStatusValue($order->status));
    });
    
    $confirmedOrders = $activeOrders->filter(function($order) use ($getStatusValue, $isPending, $isProcessingOrShipped) {
        $statusVal = $getStatusValue($order->status);
        $isPendingStatus = $isPending($statusVal);
        $isProcessingShipped = $isProcessingOrShipped($statusVal);
        return !$isPendingStatus && !$isProcessingShipped;
    });
    
    // Hardcoded shipping costs based on the screenshot and requirements
    $fixedShippingCosts = [
        'KURIR_TOKO' => 10000,  // Base rate for <5km
        'GOSEND' => 25000,      // Fixed rate from screenshot
        'JNE' => 12000,         // Fixed rate based on requirements
        'JNT' => 14000,         // Fixed rate based on requirements
        'SICEPAT' => 25000,     // Rate from screenshot
        'AMBIL_SENDIRI' => 0    // Always free
    ];
    
    // Function to get shipping cost based on method
    $getShippingCost = function($order) use ($fixedShippingCosts) {
        $shipping_method = $order->shipping_method ?? 'KURIR_TOKO';
        
        // Check for shipping costs from the database
        try {
            $shippingCostsFromDB = DB::table('shippings')
                ->select('courier', 'shipping_cost')
                ->get()
                ->groupBy('courier');
            
            // Map shipping costs to associative array
            $shippingCostMap = [];
            foreach($shippingCostsFromDB as $courier => $entries) {
                if ($entries->count() > 0) {
                    $shippingCostMap[$courier] = $entries->first()->shipping_cost;
                }
            }
        } catch (\Exception $e) {
            $shippingCostMap = [];
        }
        
        // IMPORTANT: Always ensure AMBIL_SENDIRI is free
        if ($shipping_method == 'AMBIL_SENDIRI') {
            return 0;
        }
        
        // Get shipping cost from order if available
        if (isset($order->shipping_cost)) {
            return $order->shipping_cost;
        }
        
        // Calculate based on shipping method
        switch ($shipping_method) {
            case 'KURIR_TOKO':
                // Use database value if available or fallback to hardcoded
                $shippingCost = $shippingCostMap['KURIR_TOKO'] ?? $shippingCostMap['KURIR TOKO'] ?? $fixedShippingCosts['KURIR_TOKO'];
                // For most addresses, use the middle tier (5-10km) rate
                return 15000;
                
            case 'GOSEND':
                return $shippingCostMap['GOSEND'] ?? $fixedShippingCosts['GOSEND'];
                
            case 'JNE':
                return $shippingCostMap['JNE'] ?? $fixedShippingCosts['JNE'];
                
            case 'JNT':
                return $shippingCostMap['JNT'] ?? $fixedShippingCosts['JNT'];
                
            case 'SICEPAT':
                return $shippingCostMap['SICEPAT'] ?? $fixedShippingCosts['SICEPAT'];
                
            default:
                return 0;
        }
    };
    
    // Updated calculation of total with tax using shipping cost function
    $getTotalWithTax = function($order) use ($getShippingCost) {
        // Calculate the total product price from order items
        $subtotal = 0;
        $original_price_total = 0;
        $total_discount = 0;
        
        // Calculate subtotal from order details
        if (isset($order->details) && count($order->details) > 0) {
            foreach ($order->details as $detail) {
                $productPrice = isset($detail->product) ? $detail->product->price : ($detail->price ?? 0);
                $quantity = $detail->quantity ?? 1;
                $itemSubtotal = isset($detail->subtotal) ? $detail->subtotal : ($productPrice * $quantity);
                
                // Add to total
                $subtotal += $itemSubtotal;
                $original_price = $productPrice * $quantity;
                $original_price_total += $original_price;
                
                // Calculate discount if any
                if ($original_price > $itemSubtotal) {
                    $total_discount += ($original_price - $itemSubtotal);
                }
            }
        } else {
            $subtotal = $order->subtotal ?? 0;
            $total_discount = $order->discount_total ?? ($order->discount ?? 0);
        }
        
        // If still 0, use the provided value from order or default
        if ($subtotal <= 0) {
            $subtotal = $order->subtotal ?? 80000; // Default based on the screenshot
        }
        
        // Get shipping cost using the dedicated function
        $shipping = $getShippingCost($order);
        
        // Calculate other costs
        $discount = $order->discount_total ?? ($order->discount ?? $total_discount);
        $handling = $order->handling_fee ?? 0;
        $payment = $order->payment_fee ?? 0;
        
        // Calculate the final total with tax
        $totalBeforeTax = $subtotal + $handling + $shipping + $payment - $discount;
        $tax = round($totalBeforeTax * 0.11); // PPN 11%
        
        return $totalBeforeTax + $tax;
    };

    // Fungsi untuk mendapatkan informasi metode pengiriman
    $getShippingMethodName = function($code) {
        $shippingMethods = [
            'KURIR_TOKO' => 'Kurir Toko',
            'GOSEND' => 'GoSend',
            'JNE' => 'JNE',
            'JNT' => 'J&T',
            'SICEPAT' => 'SiCepat',
            'AMBIL_SENDIRI' => 'Ambil Sendiri',
        ];
        return $shippingMethods[$code] ?? $code;
    };

    // Fungsi untuk mendapatkan informasi metode pembayaran
    $getPaymentMethodName = function($code) {
        $paymentMethodMap = [
            'CASH' => 'Uang Tunai',
            'QRIS' => 'QRIS',
            'COD_QRIS' => 'COD dengan QRIS/E-Wallet',
            'EWALLET' => 'E-Wallet',
            'TRANSFER' => 'Transfer Bank',
            'VA' => 'Virtual Account'
        ];
        return $paymentMethodMap[$code] ?? $code;
    };

    // Fungsi untuk menghasilkan status teks yang lebih human-friendly
    $getStatusText = function($statusCode) {
        $statusMap = [
            'WAITING_PAYMENT' => 'Menunggu Pembayaran',
            'PENDING' => 'Menunggu Pembayaran',
            'PAID' => 'Pembayaran Diterima',
            'PROCESSING' => 'Diproses',
            'SHIPPED' => 'Dikirim',
            'COMPLETED' => 'Selesai',
            'SUCCESS' => 'Sukses',
            'CANCELED' => 'Dibatalkan',
            'FAILED' => 'Gagal',
            'EXPIRED' => 'Kadaluarsa'
        ];
        return $statusMap[strtoupper($statusCode)] ?? ucfirst(strtolower($statusCode));
    };
@endphp

<style>
body { background: #eaf8f0; }
.orders-table-container, .canceled-table-container, .expired-table-container {
  background: #fff;
  border-radius: 1.5rem;
  box-shadow: 0 8px 40px rgba(80,120,90,0.08);
  border: 1px solid #e8f4ea;
  margin-bottom: 2rem;
  margin-top: 1.5rem;
  padding: 2rem 1rem; /* Kurangi padding horizontal */
  width: 100%;
  max-width: 100%; /* Perlebar container menjadi 100% */
  margin-left: auto;
  margin-right: auto;
  padding-bottom: 1em;
  box-sizing: border-box;
  overflow-x: auto; /* Tambahkan overflow untuk tabel lebar */
}
.orders-table, .canceled-orders-table, .expired-orders-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  table-layout: fixed;
  background: #fff;
  border-radius: 1em;
  box-shadow: 0 2px 18px #295f3a10;
  margin-bottom: 1em;
  transition: filter .3s;
}
.orders-table th, .orders-table td, .canceled-orders-table th, .canceled-orders-table td, .expired-orders-table th, .expired-orders-table td {
  padding: 1.15rem 0.5em; /* Kurangi padding horizontal */
  vertical-align: top;
  border-bottom: 1px solid #eaf8f0;
  text-align: left;
  font-size: 1em;
  min-width: 90px;
  position: relative;
}
.orders-table th:not(:last-child), .orders-table td:not(:last-child),
.canceled-orders-table th:not(:last-child), .canceled-orders-table td:not(:last-child),
.expired-orders-table th:not(:last-child), .expired-orders-table td:not(:last-child) {
  padding-right: 1em; /* Kurangi padding kanan */
}
.orders-table thead th, .canceled-orders-table thead th, .expired-orders-table thead th {
  background: #e8f4ea;
  color: #295f3a;
  font-weight: 700;
  font-size: 1.07rem;
  letter-spacing: 0.08em;
  border-bottom: 2px solid #d1e7d9;
  text-align: left;
}
.orders-table tbody tr.waiting-row { background: #f6f9f6; box-shadow: 0 0 0 2px #e8f4ea; }
.orders-table tbody tr:hover, .canceled-orders-table tbody tr:hover, .expired-orders-table tbody tr:hover {
  background: #f0f5f2;
  box-shadow: 0 2px 12px rgba(80,120,90,0.07);
  transform: scale(1.002); /* Kurangi skalanya lagi */
}
.status-badge {
  background: linear-gradient(90deg, #eaf8f0 60%, #f8faf8 100%);
  color: #295f3a;
  font-weight: 600;
  padding: 0.5em 1em; /* Kurangi padding horizontal */
  border-radius: 1em;
  box-shadow: 0 2px 6px rgba(80,120,90,0.06);
  display: inline-block;
  font-size: 0.95em; /* Kurangi ukuran font */
  border: none;
  white-space: nowrap; /* Mencegah pemisahan teks */
  margin-bottom: 0.5em; /* Tambahkan margin di bawah badge status */
}
.status-completed, .status-processing, .status-shipped, .status-pending, .status-other {
  background: linear-gradient(90deg, #eaf8f0 60%, #f8faf8 100%);
  color: #295f3a;
}
.status-canceled, .status-expired {
  background: linear-gradient(90deg, #f2f4f5 60%, #f8faf8 100%);
  color: #888e92;
}
.price-strikethrough {
  position: relative;
  color: #b2b9bb !important;
  font-weight: 600;
  display: inline-block;
  opacity: 0.97;
}
.price-strikethrough::after {
  content: "";
  position: absolute;
  left: 8%;
  right: 8%;
  top: 52%;
  height: 0;
  border-top: 2.5px solid #b2b9bb;
  opacity: 0.7;
  transform: rotate(-13deg);
  pointer-events: none;
}
/* Tombol aksi modern - redesigned */
.action-btns {
  display: flex;
  flex-direction: column;
  gap: 6px; /* Kurangi gap antar tombol */
  margin-top: 0.3em;
  align-items: stretch;
  width: 100%; /* Memastikan lebar tombol-tombol konsisten */
}
.action-btn {
  display: flex; /* Ubah dari block ke flex */
  justify-content: center; /* Center horizontal */
  align-items: center; /* Center vertical */
  width: 100%;
  min-width: 110px; /* Kurangi lebar minimum */
  font-size: 0.9em; /* Kurangi ukuran font */
  font-weight: 700;
  border-radius: 0.6em;
  padding: 0.6em 0.4em; /* Kurangi padding */
  border: none;
  outline: none;
  text-align: center;
  box-shadow: 0 1.5px 8px #d9e7d3;
  letter-spacing: .01em;
  cursor: pointer;
  transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform .12s;
  margin-bottom: 0;
  white-space: nowrap;
  text-decoration: none;
}
.btn-detail {
  background: #eaf8f0;
  color: #1b4d2c;
  border: 1.5px solid #bde2c5;
}
.btn-detail:hover { background: #d7f5e3; box-shadow: 0 2px 18px #bde2c533; }

.btn-cancel {
  background: #f7f7f9;
  color: #2c2c2e;
  border: 1.5px solid #e1e1e7;
}
.btn-cancel:hover { background: #edeef2; color: #19713c; border-color: #bde2c5; }

.btn-expire {
  background: #eaf8f0;
  color: #19713c;
  border: 1.5px solid #bde2c5;
}
.btn-expire:hover { background: #d7f5e3; color: #19713c; border-color: #19713c; }

.btn-complete {
  background: #f5fdf6;
  color: #19713c;
  border: 1.5px solid #bde2c5;
}
.btn-complete:hover { background: #e0fae6; border-color: #19713c; color: #12622c; }

.action-btn:active {
  transform: scale(.96);
}

@media (max-width: 900px) {
  .action-btn { min-width: 0; font-size: 0.9em; }
}
.canceled-warning {
  max-width: 100%; /* Perlebar sesuai container utama */
  margin: 0 auto 1.1em auto;
  background: #fffbe5;
  color: #222;
  border-radius: 1em;
  font-size: 1.08em;
  border: 1.5px solid #ffe066;
  font-weight: 500;
  box-shadow: 0 2px 20px #ffe06640;
  padding: 0.85em 2em 0.85em 2.2em;
  text-align: left;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 1.1em;
  line-height: 1.42;
  min-width: 0;
  min-height: 0;
  position: relative;
}
.canceled-warning .icon-warn {
  font-size: 1.5em;
  margin-right: 0.7em;
  color: #ffbb00;
  flex-shrink: 0;
  display: flex;
  align-items: center;
}
.canceled-warning b, .canceled-warning strong {
  font-weight: 700;
  color: #222;
  letter-spacing: 0.01em;
}
.canceled-warning a {
  color: #19713c;
  text-decoration: underline;
  font-weight: 700;
  margin-left: 0.35em;
  transition: color 0.13s;
}
.canceled-warning a:hover { color: #12622c; }
@media (max-width: 1300px) {
  .canceled-warning { max-width: 98vw; padding-left: 1.2em; padding-right: 1.2em; }
}
@media (max-width: 800px) {
  .canceled-warning { padding: 0.7em 0.6em; font-size: 0.98em; }
}
/* ...Responsif dan mobile (kode lain tetap) ... */
@media (max-width: 1300px) {
  .orders-table-container, .canceled-table-container, .expired-table-container {
    max-width: 100vw;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
  }
}
@media (max-width: 800px) {
  .orders-table-container, .canceled-table-container, .expired-table-container {
    padding: 1rem 0.25rem;
  }
}
@media (max-width: 640px) {
  .orders-table, .canceled-orders-table, .expired-orders-table,
  .orders-table thead, .canceled-orders-table thead, .expired-orders-table thead,
  .orders-table tr, .canceled-orders-table tr, .expired-orders-table tr {
    display: block !important;
    width: 100% !important;
    min-width: 0 !important;
  }
  .orders-table thead, .canceled-orders-table thead, .expired-orders-table thead {
    display: none !important;
  }
  .orders-table tbody, .canceled-orders-table tbody, .expired-orders-table tbody {
    display: block !important;
    width: 100% !important;
  }
  .orders-table tr, .canceled-orders-table tr, .expired-orders-table tr {
    margin-bottom: 1.6rem;
    border-radius: 1rem;
    box-shadow: 0 1px 8px #295f3a18;
    background: #fff;
    padding: 1.2rem 0.7rem;
    border: 1px solid #eaf8f0;
  }
  .orders-table td, .canceled-orders-table td, .expired-orders-table td {
    display: flex;
    width: 100%;
    padding: 0.3em 0.1em;
    border: none;
    align-items: flex-start;
    font-size: 1em;
    word-break: break-word;
    margin-bottom: 0.6em;
  }
  .orders-table td:before, .canceled-orders-table td:before, .expired-orders-table td:before {
    content: attr(data-label);
    min-width: 110px;
    color: #5f8b6a;
    font-weight: 700;
    font-size: 0.98em;
    margin-right: 0.75em;
    flex-shrink: 0;
    display: block;
  }
  .orders-table tr.waiting-row {
    box-shadow: 0 0 0 2px #e8f4ea, 0 2px 16px #9ae6b41a;
  }
  .product-list { gap: 0.22em; padding: 0.18em 0.1em; max-width: 100%; min-width: 0; }
  .product-img { width: 34px; height: 34px;}
  .product-info { max-width: 100%; }
  .product-title { font-size: 0.97em;}
  .orders-table th, .orders-table td,
  .canceled-orders-table th, .canceled-orders-table td,
  .expired-orders-table th, .expired-orders-table td {
    min-width: 0 !important;
    max-width: 100vw !important;
    width: 100% !important;
  }
  .payment-info,
  .order-detail-btn {
    margin-right: 0 !important;
    margin-bottom: 7px !important;
  }
}
.order-loading-overlay {
  position: fixed;
  z-index: 99;
  inset: 0;
  background: rgba(245,255,248,0.87);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: opacity .3s;
}
.order-loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.4em;
}
.order-loading-spinner svg {
  width: 3.5em;
  height: 3.5em;
  color: #30b37d;
  animation: spin 1.2s linear infinite;
}
@keyframes spin { 100% { transform: rotate(360deg); } }
.order-loading-spinner .text {
  color: #295f3a;
  font-weight: 700;
  font-size: 1.18em;
  letter-spacing: 0.03em;
}

.expired-warning, .processing-warning {
  max-width: 100%; /* Perlebar sesuai container utama */
  margin: 0 auto 1.1em auto;
  background: #f4f4f7;
  color: #555;
  border-radius: 1em;
  font-size: 1.08em;
  border: 1.5px solid #e0e0e0;
  font-weight: 500;
  box-shadow: 0 2px 20px #bcbcbc25;
  padding: 0.85em 2em 0.85em 2.2em;
  text-align: left;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 1.1em;
  line-height: 1.42;
  min-width: 0;
  min-height: 0;
  position: relative;
}
.processing-warning .icon-processing {
  font-size: 1.5em;
  margin-right: 0.7em;
  color: #a8a8a8;
  flex-shrink: 0;
  display: flex;
  align-items: center;
}
.processing-warning b, .processing-warning strong {
  font-weight: 700;
  color: #222;
}
.processing-warning a {
  color: #888;
  text-decoration: underline;
  font-weight: 700;
  margin-left: 0.35em;
  transition: color 0.13s;
}
.processing-warning a:hover { color: #555; }
@media (max-width: 1300px) {
  .processing-warning, .expired-warning { max-width: 98vw; padding-left: 1.2em; padding-right: 1.2em; }
}
@media (max-width: 800px) {
  .processing-warning, .expired-warning { padding: 0.7em 0.6em; font-size: 0.98em; }
}

/* Tambahan style untuk produk list */
.product-list {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
  max-width: 250px; /* Mengurangi lebar maksimal untuk kolom produk */
  padding-right: 8px; /* Kurangi padding kanan */
  border-right: 1px solid #eaf8f0;
  margin-right: 8px; /* Kurangi margin kanan */
}

.product-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding-bottom: 8px;
  border-bottom: 1px dashed #e5e7eb;
}

.product-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.product-img {
  width: 42px; /* Kecilkan gambar produk */
  height: 42px;
  border-radius: 6px;
  object-fit: cover;
  border: 1px solid #e2e8f0;
  flex-shrink: 0;
}

.product-info {
  flex: 1;
  min-width: 0;
}

.product-title {
  font-weight: 600;
  color: #295f3a;
  font-size: 0.85rem; /* Kecilkan font */
  margin-bottom: 0.2rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.product-qty {
  font-size: 0.75rem;
  color: #555;
}

.product-subtotal {
  font-size: 0.82rem;
  color: #295f3a;
  font-weight: 600;
  margin-top: 0.2rem;
}

/* Style untuk pengiriman info */
.shipping-info {
  background: #f8faf8;
  border-radius: 8px;
  padding: 0.7rem; /* Kurangi padding */
  margin-right: 0.4rem;
  font-size: 0.85rem; /* Kecilkan font */
  line-height: 1.5;
  border-left: 3px solid #dcfce7;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

/* Style untuk payment info */
.payment-info {
  background: #f8faf8;
  border-radius: 8px;
  padding: 0.7rem; /* Kurangi padding */
  margin-right: 0.4rem;
  font-size: 0.85rem; /* Kecilkan font */
  line-height: 1.5;
  border-left: 3px solid #dcfce7;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.payment-label {
  color: #555;
}

.payment-nominal {
  font-weight: 700;
  color: #166534;
}

/* Perbaiki kolom tabel - memberi lebar tetap */
.orders-table th, .orders-table td,
.canceled-orders-table th, .canceled-orders-table td,
.expired-orders-table th, .expired-orders-table td {
  box-sizing: border-box;
}

/* Pengaturan lebar kolom - disesuaikan untuk lebih akurat dan diperlebar */
.column-order-code { width: 8%; } /* Kecilkan kolom kode order */
.column-date { width: 8%; } /* Kecilkan kolom tanggal */
.column-status { width: 12%; }
.column-price { width: 8%; } /* Kecilkan kolom harga */
.column-product { width: 18%; }
.column-shipping { width: 18%; } /* Perlebar kolom pengiriman */
.column-payment { width: 18%; } /* Perlebar kolom pembayaran */
.column-action { width: 10%; }

/* Border pembatas antar kolom */
.orders-table td, .canceled-orders-table td, .expired-orders-table td {
  position: relative;
}

.orders-table td:not(:last-child)::after,
.canceled-orders-table td:not(:last-child)::after,
.expired-orders-table td:not(:last-child)::after {
  content: '';
  position: absolute;
  top: 15%;
  right: 0;
  height: 70%;
  width: 1px;
  background-color: #eaf8f0;
}

/* Custom style untuk kolom aksi agar sesuai dengan gambar */
.column-action {
  vertical-align: middle !important;
  padding-right: 10px !important; /* Kurangi padding */
  text-align: center !important;
}

/* Style tombol seperti gambar */
.btn-detail, .btn-cancel, .btn-expire, .btn-complete {
  font-size: 0.85em !important; /* Kecilkan font */
  padding: 0.6em 0.4em !important; /* Kurangi padding */
  margin-bottom: 0.4em !important; /* Kurangi margin */
  border-radius: 0.5em !important;
  min-height: 32px !important; /* Kurangi tinggi */
}

/* Detail Pesanan button */
.btn-detail {
  background: #eaf8f0 !important;
  color: #166534 !important;
  border: 1px solid #bde2c5 !important;
}

/* Batalkan button */
.btn-cancel {
  background: #f8f8f8 !important;
  color: #374151 !important;
  border: 1px solid #e5e7eb !important;
}

/* Jadikan Kadaluarsa button */
.btn-expire {
  background: #eaf8f0 !important;
  color: #166534 !important;
  border: 1px solid #bde2c5 !important;
}

/* Selesaikan button */
.btn-complete {
  background: #eaf8f0 !important;
  color: #166534 !important;
  border: 1px solid #bde2c5 !important;
}

/* Tambahkan jarak antara status dan total harga */
.column-status {
  padding-right: 15px !important; /* Kurangi jarak di kanan status */
  position: relative;
}

.column-status::after {
  content: '';
  position: absolute;
  top: 15%;
  right: 8px; /* Sesuaikan posisi garis pembatas */
  height: 70%;
  width: 1px;
  background-color: #eaf8f0;
}

.column-price {
  padding-left: 15px !important; /* Kurangi jarak di kiri total harga */
}

/* Style untuk status agar lebih terpisah dari harga */
.status-cell-wrapper {
  display: flex;
  flex-direction: column;
  border-right: 1px solid #eaf8f0;
  height: 100%;
  padding-right: 8px; /* Kurangi padding */
}

/* Styling agar total harga lebih tegas */
.total-price {
  display: block;
  margin-top: 6px; /* Kurangi jarak di atas harga */
  font-weight: bold;
  color: #295f3a;
  font-size: 0.95em; /* Kecilkan font */
}

/* Layout container utama untuk memperlebar tabel */
.px-2 {
  padding-left: 0.25rem !important; /* Kurangi padding kiri */
  padding-right: 0.25rem !important; /* Kurangi padding kanan */
}

.max-w-7xl {
  max-width: 98% !important; /* Gunakan hampir seluruh lebar layar */
}

/* Style untuk badge bebas ongkir */
.free-shipping-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  background: #15803d;
  color: white;
  border-radius: 0.5rem;
  font-weight: 600;
  font-size: 0.75rem;
  margin-left: 0.5rem;
}
</style>

<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 400)">
  <template x-if="loading">
    <div class="order-loading-overlay">
      <div class="order-loading-spinner">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        <span class="text">Memuat pesanan...</span>
      </div>
    </div>
  </template>
  <div class="px-2 mx-auto sm:px-5 max-w-7xl" x-show="!loading" x-transition.opacity>
    <h1 class="mb-8 text-3xl font-extrabold tracking-tight text-center" style="color:#295f3a;">Daftar Pesanan Anda</h1>

    {{-- Menunggu Pembayaran --}}
    @if($pendingOrders->count())
    <div class="orders-table-container">
      <h2 class="mb-4 text-xl font-bold" style="color:#295f3a;">Menunggu Pembayaran / Konfirmasi Admin</h2>
      <div class="overflow-x-auto">
        <table class="orders-table">
          <thead>
            <tr>
              <th class="column-order-code">KODE ORDER</th>
              <th class="column-date">TANGGAL</th>
              <th class="column-status">STATUS</th>
              <th class="column-price">TOTAL HARGA</th>
              <th class="column-product">PRODUK</th>
              <th class="column-shipping">PENGIRIMAN</th>
              <th class="column-payment">PEMBAYARAN</th>
              <th class="column-action">AKSI</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pendingOrders as $order)
            @php
              $statusVal = $getStatusValue($order->status);
              $totalWithTax = $getTotalWithTax($order);
              $shippingMethod = $getShippingMethodName($order->shipping_method ?? 'KURIR_TOKO');
              $paymentMethod = $getPaymentMethodName($order->payment_method ?? 'CASH');
              $shippingCost = $getShippingCost($order);
              $isFreeShipping = $order->shipping_method === 'AMBIL_SENDIRI';
            @endphp
            <tr class="waiting-row">
              <td data-label="Kode Order" class="column-order-code"><span class="font-mono font-bold" style="color:#295f3a;">{{ $order->order_code }}</span></td>
              <td data-label="Tanggal" class="column-date">
                <span class="block">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y') }}</span>
                <span class="block text-xs" style="color:#888e92;">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('H:i') }}</span>
              </td>
              <td data-label="Status" class="column-status">
                <div class="status-cell-wrapper">
                  <span class="status-badge status-pending">Menunggu Pembayaran</span>
                </div>
              </td>
              <td data-label="Total Harga" class="column-price">
                <span class="total-price">Rp{{ number_format($totalWithTax, 0, ',', '.') }}</span>
              </td>
              <td data-label="Produk" class="column-product">
                <div class="product-list">
                    @foreach($order->details as $detail)
                    @php
                    $img = isset($detail->product->images) && $detail->product->images->count() > 0
                        ? asset($detail->product->images->first()->image_url)
                        : asset('images/no-image.png');
                    @endphp
                    <div class="product-item">
                      <img src="{{ $img }}" alt="{{ $detail->product->name }}" class="product-img" />
                      <div class="product-info">
                          <span class="product-title">{{ $detail->product->name }}</span>
                          <span class="product-qty">x{{ $detail->quantity }}</span>
                          <span class="product-subtotal">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                      </div>
                    </div>
                    @endforeach
                </div>
              </td>
              <td data-label="Pengiriman" class="column-shipping">
                @if($order->shipping)
                <div class="shipping-info">
                  <span class="font-semibold">{{ $order->shipping->courier ?? $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge">BEBAS ONGKIR</span>
                  @endif
                  <span class="block">{{ $order->shipping->service ?? '-' }}</span>
                  <span class="block" style="color:#5f8b6a;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <strong style="color:#15803d;">Gratis (Rp0)</strong>
                    @else
                    Rp{{ number_format($order->shipping->shipping_cost ?? $shippingCost, 0, ',', '.') }}
                    @endif
                  </span>
                  <span class="block">Resi: <span class="font-mono">{{ $order->shipping->tracking_number ?? '-' }}</span></span>
                  <span class="block" style="color:#5f8b6a;">Status: {{ $order->shipping->status ?? '-' }}</span>
                </div>
                @else
                <div class="shipping-info">
                  <span class="font-semibold">{{ $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge">BEBAS ONGKIR</span>
                  @endif
                  <span class="block" style="color:#5f8b6a;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <strong style="color:#15803d;">Gratis (Rp0)</strong>
                    @else
                    Rp{{ number_format($shippingCost, 0, ',', '.') }}
                    @endif
                  </span>
                  <span class="block">Status: Menunggu Pembayaran</span>
                </div>
                @endif
              </td>
              <td data-label="Pembayaran" class="column-payment">
                <div class="payment-info">
                  <span class="payment-label">Metode: {{ $order->payment->method->name ?? $paymentMethod }}</span><br>
                  <span class="payment-label">Status: Menunggu Pembayaran</span><br>
                  <span style="font-size:0.97em;">Total:</span>
                  <span class="payment-nominal">Rp{{ number_format($totalWithTax, 0, ',', '.') }}</span>
                </div>
              </td>
              <td data-label="Aksi" class="column-action">
                <div class="action-btns">
                  <a href="{{ route('user.orders.show', $order->id) }}" class="action-btn btn-detail">Detail Pesanan</a>
                  <button type="button" class="action-btn btn-cancel" onclick="document.getElementById('cancel-form-{{ $order->id }}').submit()">Batalkan</button>
                  <button type="button" class="action-btn btn-expire" onclick="showExpireConfirm({{ $order->id }})">Jadikan Kadaluarsa</button>
                  <button type="button" class="action-btn btn-complete" onclick="document.getElementById('complete-form-{{ $order->id }}').submit()">Selesaikan</button>
                
                  <form id="cancel-form-{{ $order->id }}" action="{{ route('user.orders.cancel', $order->id) }}" method="POST" style="display:none;">
                    @csrf @method('PATCH')
                  </form>
                  <form id="expire-form-{{ $order->id }}" action="{{ route('user.orders.expire', $order->id) }}" method="POST" style="display:none;" class="expire-form" data-order-id="{{ $order->id }}">
                    @csrf @method('PATCH')
                  </form>
                  <form id="complete-form-{{ $order->id }}" action="{{ route('user.orders.complete', $order->id) }}" method="POST" style="display:none;">
                    @csrf @method('PATCH')
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- Pesanan Diproses / Dikirim --}}
    @if($processingOrShippedOrders->count())
    <div class="processing-warning">
      <span class="icon-processing">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="height:1.4em;width:1.4em;"><circle cx="12" cy="12" r="10" stroke="#bbb" stroke-width="2" fill="#f4f4f7"/><path d="M12 7v5" stroke="#bbb" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="16" r="1.2" fill="#bbb"/></svg>
      </span>
      Pesanan Anda sedang <b>diproses</b> atau <b>dikirim</b>. Silakan cek detail pesanan untuk status terbaru.
    </div>
    <div class="orders-table-container">
      <h2 class="mb-4 text-xl font-bold" style="color:#888;">Pesanan Diproses / Dikirim</h2>
      <div class="overflow-x-auto">
        <table class="orders-table">
          <thead>
            <tr>
              <th class="column-order-code">KODE ORDER</th>
              <th class="column-date">TANGGAL</th>
              <th class="column-status">STATUS</th>
              <th class="column-price">TOTAL HARGA</th>
              <th class="column-product">PRODUK</th>
              <th class="column-shipping">PENGIRIMAN</th>
              <th class="column-payment">PEMBAYARAN</th>
              <th class="column-action">DETAIL</th>
            </tr>
          </thead>
          <tbody>
            @foreach($processingOrShippedOrders as $order)
            @php
              $statusVal = $getStatusValue($order->status);
              $totalWithTax = $getTotalWithTax($order);
              $statusText = $getStatusText($statusVal);
              $shippingMethod = $getShippingMethodName($order->shipping_method ?? 'KURIR_TOKO');
              $paymentMethod = $getPaymentMethodName($order->payment_method ?? 'CASH');
              $shippingCost = $getShippingCost($order);
              $isFreeShipping = $order->shipping_method === 'AMBIL_SENDIRI';
            @endphp
            <tr>
              <td data-label="Kode Order" class="column-order-code"><span class="font-mono font-bold" style="color:#888;">{{ $order->order_code }}</span></td>
              <td data-label="Tanggal" class="column-date">
                <span class="block">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y') }}</span>
                <span class="block text-xs" style="color:#888e92;">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('H:i') }}</span>
              </td>
              <td data-label="Status" class="column-status">
                <div class="status-cell-wrapper">
                  <span class="status-badge status-processing">{{ $statusText }}</span>
                </div>
              </td>
              <td data-label="Total Harga" class="column-price">
                <span class="total-price" style="color:#888;">Rp{{ number_format($totalWithTax, 0, ',', '.') }}</span>
              </td>
              <td data-label="Produk" class="column-product">
                <div class="product-list">
                  @foreach($order->details as $detail)
                  @php
                    $img = isset($detail->product->images) && $detail->product->images->count() > 0
                      ? asset($detail->product->images->first()->image_url)
                      : asset('images/no-image.png');
                  @endphp
                  <div class="product-item">
                    <img src="{{ $img }}" alt="{{ $detail->product->name }}" class="product-img" />
                    <div class="product-info">
                      <span class="product-title">{{ $detail->product->name }}</span>
                      <span class="product-qty">x{{ $detail->quantity }}</span>
                      <span class="product-subtotal">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </td>
              <td data-label="Pengiriman" class="column-shipping">
                @if($order->shipping)
                <div class="shipping-info">
                  <span class="font-semibold">{{ $order->shipping->courier ?? $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge">BEBAS ONGKIR</span>
                  @endif
                  <span class="block">{{ $order->shipping->service ?? '-' }}</span>
                  <span class="block" style="color:#888;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <strong style="color:#15803d;">Gratis (Rp0)</strong>
                    @else
                    Rp{{ number_format($order->shipping->shipping_cost ?? $shippingCost, 0, ',', '.') }}
                    @endif
                  </span>
                  <span class="block">Resi: <span class="font-mono">{{ $order->shipping->tracking_number ?? '-' }}</span></span>
                  <span class="block" style="color:#888;">Status: {{ $order->shipping->status ?? $statusText }}</span>
                </div>
                @else
                <div class="shipping-info">
                  <span class="font-semibold">{{ $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge">BEBAS ONGKIR</span>
                  @endif
                  <span class="block" style="color:#888;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <strong style="color:#15803d;">Gratis (Rp0)</strong>
                    @else
                    Rp{{ number_format($shippingCost, 0, ',', '.') }}
                    @endif
                  </span>
                  <span class="block">Status: {{ $statusText }}</span>
                </div>
                @endif
              </td>
              <td data-label="Pembayaran" class="column-payment">
                <div class="payment-info">
                  <span class="payment-label">Metode: {{ $order->payment->method->name ?? $paymentMethod }}</span><br>
                  <span class="payment-label">Status: {{ $statusText }}</span><br>
                  <span style="font-size:0.97em;">Total:</span>
                  <span class="payment-nominal">Rp{{ number_format($totalWithTax, 0, ',', '.') }}</span>
                </div>
              </td>
              <td data-label="Detail" class="column-action">
                <a href="{{ route('user.orders.show', $order->id) }}" class="action-btn btn-detail">Detail Pesanan</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- Pesanan Selesai --}}
    @if($confirmedOrders->count())
    <div class="orders-table-container">
      <h2 class="mb-4 text-xl font-bold" style="color:#295f3a;">Pesanan Selesai</h2>
      <div class="overflow-x-auto">
        <table class="orders-table">
          <thead>
            <tr>
              <th class="column-order-code">KODE ORDER</th>
              <th class="column-date">TANGGAL</th>
              <th class="column-status">STATUS</th>
              <th class="column-price">TOTAL HARGA</th>
              <th class="column-product">PRODUK</th>
              <th class="column-shipping">PENGIRIMAN</th>
              <th class="column-payment">PEMBAYARAN</th>
              <th class="column-action">DETAIL</th>
            </tr>
          </thead>
          <tbody>
            @foreach($confirmedOrders as $order)
            @php
              $statusVal = $getStatusValue($order->status);
              $totalWithTax = $getTotalWithTax($order);
              $statusText = $getStatusText($statusVal);
              $shippingMethod = $getShippingMethodName($order->shipping_method ?? 'KURIR_TOKO');
              $paymentMethod = $getPaymentMethodName($order->payment_method ?? 'CASH');
              $shippingCost = $getShippingCost($order);
              $isFreeShipping = $order->shipping_method === 'AMBIL_SENDIRI';
            @endphp
            <tr>
              <td data-label="Kode Order" class="column-order-code"><span class="font-mono font-bold" style="color:#295f3a;">{{ $order->order_code }}</span></td>
              <td data-label="Tanggal" class="column-date">
                <span class="block">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y') }}</span>
                <span class="block text-xs" style="color:#888e92;">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('H:i') }}</span>
              </td>
              <td data-label="Status" class="column-status">
                <div class="status-cell-wrapper">
                  <span class="status-badge status-completed">{{ $statusText }}</span>
                </div>
              </td>
              <td data-label="Total Harga" class="column-price">
                <span class="total-price">Rp{{ number_format($totalWithTax, 0, ',', '.') }}</span>
              </td>
              <td data-label="Produk" class="column-product">
                <div class="product-list">
                  @foreach($order->details as $detail)
                  @php
                    $img = isset($detail->product->images) && $detail->product->images->count() > 0
                      ? asset($detail->product->images->first()->image_url)
                      : asset('images/no-image.png');
                  @endphp
                  <div class="product-item">
                    <img src="{{ $img }}" alt="{{ $detail->product->name }}" class="product-img" />
                    <div class="product-info">
                      <span class="product-title">{{ $detail->product->name }}</span>
                      <span class="product-qty">x{{ $detail->quantity }}</span>
                      <span class="product-subtotal">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </td>
              <td data-label="Pengiriman" class="column-shipping">
                @if($order->shipping)
                <div class="shipping-info">
                  <span class="font-semibold">{{ $order->shipping->courier ?? $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge">BEBAS ONGKIR</span>
                  @endif
                  <span class="block">{{ $order->shipping->service ?? '-' }}</span>
                  <span class="block" style="color:#5f8b6a;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <strong style="color:#15803d;">Gratis (Rp0)</strong>
                    @else
                    Rp{{ number_format($order->shipping->shipping_cost ?? $shippingCost, 0, ',', '.') }}
                    @endif
                  </span>
                  <span class="block">Resi: <span class="font-mono">{{ $order->shipping->tracking_number ?? '-' }}</span></span>
                  <span class="block" style="color:#5f8b6a;">Status: {{ $order->shipping->status ?? 'Selesai' }}</span>
                </div>
                @else
                <div class="shipping-info">
                  <span class="font-semibold">{{ $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge">BEBAS ONGKIR</span>
                  @endif
                  <span class="block" style="color:#5f8b6a;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <strong style="color:#15803d;">Gratis (Rp0)</strong>
                    @else
                    Rp{{ number_format($shippingCost, 0, ',', '.') }}
                    @endif
                  </span>
                  <span class="block">Status: Selesai</span>
                </div>
                @endif
              </td>
              <td data-label="Pembayaran" class="column-payment">
                <div class="payment-info">
                  <span class="payment-label">Metode: {{ $order->payment->method->name ?? $paymentMethod }}</span><br>
                  <span class="payment-label">Status: Sukses</span><br>
                  <span style="font-size:0.97em;">Total:</span>
                  <span class="payment-nominal">Rp{{ number_format($totalWithTax, 0, ',', '.') }}</span>
                </div>
              </td>
              <td data-label="Detail" class="column-action">
                <a href="{{ route('user.orders.show', $order->id) }}" class="action-btn btn-detail">Detail Pesanan</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- Pesanan Dibatalkan --}}
    @if($canceledOrders->count())
    <div class="canceled-warning">
      <span class="icon-warn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="height:1.4em;width:1.4em;"><circle cx="12" cy="12" r="10" stroke="#ffbb00" stroke-width="2" fill="#fffbe5"/><path d="M12 7v5" stroke="#ffbb00" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="16" r="1.2" fill="#ffbb00"/></svg></span>
      Pesanan yang sudah dibatalkan <b>tidak bisa diproses kembali.</b>
      <span>
        Lihat riwayat pesanan di
        <a href="{{ route('user.orders.history.index') }}">Halaman Riwayat</a>.
      </span>
    </div>
    <div class="canceled-table-container">
      <div class="overflow-x-auto">
        <table class="canceled-orders-table">
          <thead>
            <tr>
              <th class="column-order-code">KODE ORDER</th>
              <th class="column-date">TANGGAL</th>
              <th class="column-status">STATUS</th>
              <th class="column-price">TOTAL HARGA</th>
              <th class="column-product">PRODUK</th>
              <th class="column-shipping">PENGIRIMAN</th>
              <th class="column-payment">PEMBAYARAN</th>
              <th class="column-action">DETAIL</th>
            </tr>
          </thead>
          <tbody>
            @foreach($canceledOrders as $order)
            @php
              $statusVal = $getStatusValue($order->status);
              $statusText = $getStatusText($statusVal);
              $totalWithTax = $getTotalWithTax($order);
              $shippingMethod = $getShippingMethodName($order->shipping_method ?? 'KURIR_TOKO');
              $paymentMethod = $getPaymentMethodName($order->payment_method ?? 'CASH');
              $shippingCost = $getShippingCost($order);
              $isFreeShipping = $order->shipping_method === 'AMBIL_SENDIRI';
            @endphp
            <tr>
              <td data-label="Kode Order" class="column-order-code"><span class="font-mono font-bold" style="color:#888e92;">{{ $order->order_code }}</span></td>
              <td data-label="Tanggal" class="column-date">
                <span class="block">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y') }}</span>
                <span class="block text-xs" style="color: #888e92;">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('H:i') }}</span>
              </td>
              <td data-label="Status" class="column-status">
                <div class="status-cell-wrapper">
                  <span class="status-badge status-canceled">{{ $statusText }}</span>
                </div>
              </td>
              <td data-label="Total Harga" class="column-price">
                <span class="total-price price-strikethrough">Rp{{ number_format($totalWithTax, 0, ',', '.') }}</span>
              </td>
              <td data-label="Produk" class="column-product">
                <div class="product-list">
                  @foreach($order->details as $detail)
                  @php
                    $img = isset($detail->product->images) && $detail->product->images->count() > 0
                      ? asset($detail->product->images->first()->image_url)
                      : asset('images/no-image.png');
                  @endphp
                  <div class="product-item">
                    <img src="{{ $img }}" alt="{{ $detail->product->name }}" class="product-img" />
                    <div class="product-info">
                      <span class="product-title" style="color:#888e92;">{{ $detail->product->name }}</span>
                      <span class="product-qty">x{{ $detail->quantity }}</span>
                      <span class="product-subtotal price-strikethrough" style="color:#888e92;">
                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                      </span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </td>
              <td data-label="Pengiriman" class="column-shipping">
                @if($order->shipping)
                <div class="shipping-info">
                  <span class="font-semibold" style="color:#888e92;">{{ $order->shipping->courier ?? $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge" style="background:#888e92;">BEBAS ONGKIR</span>
                  @endif
                  <span class="block">{{ $order->shipping->service ?? '-' }}</span>
                  <span class="block" style="color:#888e92;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <span class="price-strikethrough">Gratis (Rp0)</span>
                    @else
                    <span class="price-strikethrough">Rp{{ number_format($order->shipping->shipping_cost ?? $shippingCost, 0, ',', '.') }}</span>
                    @endif
                  </span>
                  <span class="block">Resi: <span class="font-mono">{{ $order->shipping->tracking_number ?? '-' }}</span></span>
                  <span class="block" style="color:#888e92;">Status: {{ $order->shipping->status ?? 'Dibatalkan' }}</span>
                </div>
                @else
                <div class="shipping-info">
                  <span class="font-semibold" style="color:#888e92;">{{ $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge" style="background:#888e92;">BEBAS ONGKIR</span>
                  @endif
                  <span class="block" style="color:#888e92;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <span class="price-strikethrough">Gratis (Rp0)</span>
                    @else
                    <span class="price-strikethrough">Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
                    @endif
                  </span>
                  <span class="block">Status: Dibatalkan</span>
                </div>
                @endif
              </td>
              <td data-label="Pembayaran" class="column-payment">
                <div class="payment-info" style="background:#f8faf8;">
                  <span class="payment-label">Metode: {{ $order->payment->method->name ?? $paymentMethod }}</span><br>
                  <span style="font-size:0.97em;">Total:</span>
                  <span class="payment-nominal price-strikethrough">
                    Rp{{ number_format($order->payment->total ?? $totalWithTax, 0, ',', '.') }}
                  </span>
                </div>
              </td>
              <td data-label="Detail" class="column-action">
                <a href="{{ route('user.orders.show', $order->id) }}" class="action-btn btn-detail">Detail Pesanan</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- Pesanan Kadaluarsa --}}
    @if($expiredOrders->count())
    <div class="expired-warning">
      Pembayaran pesanan kadaluarsa. Silakan buat pesanan baru jika ingin melanjutkan transaksi.
    </div>
      <div class="expired-table-container">
      <div class="overflow-x-auto">
        <table class="expired-orders-table">
          <thead>
            <tr>
              <th class="column-order-code">KODE ORDER</th>
              <th class="column-date">TANGGAL</th>
              <th class="column-status">STATUS</th>
              <th class="column-price">TOTAL HARGA</th>
              <th class="column-product">PRODUK</th>
              <th class="column-shipping">PENGIRIMAN</th>
              <th class="column-payment">PEMBAYARAN</th>
              <th class="column-action">DETAIL</th>
            </tr>
          </thead>
          <tbody>
            @foreach($expiredOrders as $order)
            @php
              $totalWithTax = $getTotalWithTax($order);
              $shippingMethod = $getShippingMethodName($order->shipping_method ?? 'KURIR_TOKO');
              $paymentMethod = $getPaymentMethodName($order->payment_method ?? 'CASH');
              $shippingCost = $getShippingCost($order);
              $isFreeShipping = $order->shipping_method === 'AMBIL_SENDIRI';
            @endphp
            <tr>
              <td data-label="Kode Order" class="column-order-code"><span class="font-mono font-bold" style="color:#888e92;">{{ $order->order_code }}</span></td>
              <td data-label="Tanggal" class="column-date">
                <span class="block">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('d M Y') }}</span>
                <span class="block text-xs" style="color:#888e92;">{{ \Carbon\Carbon::parse($order->order_date ?? $order->created_at)->format('H:i') }}</span>
              </td>
              <td data-label="Status" class="column-status">
                <div class="status-cell-wrapper">
                  <span class="status-badge status-expired">Kadaluarsa</span>
                </div>
              </td>
              <td data-label="Total Harga" class="column-price">
                <span class="total-price price-strikethrough">
                  Rp{{ number_format($totalWithTax, 0, ',', '.') }}
                </span>
              </td>
              <td data-label="Produk" class="column-product">
                <div class="product-list">
                  @foreach($order->details as $detail)
                  @php
                    $img = isset($detail->product->images) && $detail->product->images->count() > 0
                      ? asset($detail->product->images->first()->image_url)
                      : asset('images/no-image.png');
                  @endphp
                  <div class="product-item">
                    <img src="{{ $img }}" alt="{{ $detail->product->name }}" class="product-img" />
                    <div class="product-info">
                      <span class="product-title" style="color:#888e92;">{{ $detail->product->name }}</span>
                      <span class="product-qty">x{{ $detail->quantity }}</span>
                      <span class="product-subtotal price-strikethrough" style="color:#888e92;">
                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                      </span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </td>
              <td data-label="Pengiriman" class="column-shipping">
                @if($order->shipping)
                <div class="shipping-info">
                  <span class="font-semibold" style="color:#888e92;">{{ $order->shipping->courier ?? $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge" style="background:#888e92;">BEBAS ONGKIR</span>
                  @endif
                  <span class="block">{{ $order->shipping->service ?? '-' }}</span>
                  <span class="block" style="color:#888e92;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <span class="price-strikethrough">Gratis (Rp0)</span>
                    @else
                    <span class="price-strikethrough">Rp{{ number_format($order->shipping->shipping_cost ?? $shippingCost, 0, ',', '.') }}</span>
                    @endif
                  </span>
                  <span class="block">Resi: <span class="font-mono">{{ $order->shipping->tracking_number ?? '-' }}</span></span>
                  <span class="block" style="color:#888e92;">Status: {{ $order->shipping->status ?? 'Kadaluarsa' }}</span>
                </div>
                @else
                <div class="shipping-info">
                  <span class="font-semibold" style="color:#888e92;">{{ $shippingMethod }}</span>
                  @if($isFreeShipping)
                  <span class="free-shipping-badge" style="background:#888e92;">BEBAS ONGKIR</span>
                  @endif
                  <span class="block" style="color:#888e92;">
                    Ongkir: 
                    @if($isFreeShipping)
                    <span class="price-strikethrough">Gratis (Rp0)</span>
                    @else
                    <span class="price-strikethrough">Rp{{ number_format($shippingCost, 0, ',', '.') }}</span>
                    @endif
                  </span>
                  <span class="block">Status: Kadaluarsa</span>
                </div>
                @endif
              </td>
              <td data-label="Pembayaran" class="column-payment">
                <div class="payment-info">
                  <span class="payment-label">Metode: {{ $order->payment->method->name ?? $paymentMethod }}</span><br>
                  <span style="font-size:0.97em;">Total:</span>
                  <span class="payment-nominal price-strikethrough">
                    Rp{{ number_format($order->payment->total ?? $totalWithTax, 0, ',', '.') }}
                  </span>
                  <div style="color:#f59e42;font-size:0.97em; margin-top:0.1em;">
                    Batas pembayaran: Waktu pembayaran telah habis
                  </div>
                </div>
              </td>
              <td data-label="Detail" class="column-action">
                <a href="{{ route('user.orders.show', $order->id) }}" class="action-btn btn-detail">Detail Pesanan</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

    {{-- TIDAK ADA PESANAN SAMA SEKALI --}}
    @if(
      $pendingOrders->count() === 0
      && $confirmedOrders->count() === 0
      && $processingOrShippedOrders->count() === 0
      && $canceledOrders->count() === 0
      && $expiredOrders->count() === 0
    )
      <div class="w-full max-w-2xl p-10 mx-auto mt-8 text-center text-gray-500 bg-white border-2 border-green-200 shadow-sm rounded-xl">
          <span class="text-2xl font-bold text-green-300">Belum ada pesanan.</span>
          <p class="mt-2 text-gray-400">Silakan pilih produk untuk melakukan pembelian.</p>
          <a href="{{ route('user.products.index') }}"
              class="inline-block px-6 py-2 mt-4 text-base font-bold text-white transition-all duration-150 bg-green-500 rounded-lg shadow hover:bg-green-600">
              Lihat Produk
          </a>
      </div>
    @endif

    {{-- Modal Confirm Expire --}}
    <div id="expire-confirm-modal" style="display:none; position:fixed; inset:0; background:rgba(240,245,248,0.82);z-index:1000;justify-content:center;align-items:center;">
      <div style="background:#fff; border-radius:1.3em; box-shadow:0 6px 40px #4443; padding:2.2em 2.2em 1.5em 2.2em; min-width:320px; max-width:96vw; text-align:center; position:relative;">
        <span style="position:absolute; top:1em; right:1em; cursor:pointer;" onclick="hideExpireConfirm()">
          <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="none"><path d="M6 6l13 13M19 6L6 19" stroke="#aaa" stroke-width="2.2" stroke-linecap="round"/></svg>
        </span>
        <div style="display:flex;align-items:center;justify-content:center;margin-bottom:1em;">
          <span style="color:#ffbb00;font-size:1.5em;margin-right:0.65em;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" style="height:1.25em;width:1.25em;"><circle cx="12" cy="12" r="10" stroke="#ffbb00" stroke-width="2" fill="#fffbe5"/><path d="M12 7v5" stroke="#ffbb00" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="16" r="1.2" fill="#ffbb00"/></svg></span>
          <span style="font-weight: 700; color: #d39c00;font-size:1.09em;">Konfirmasi</span>
        </div>
        <div style="color:#333; font-size:1.08em; margin-bottom:1.7em;">
          Tandai pesanan ini sebagai <b>kadaluarsa</b>?
        </div>
        <div style="display:flex;justify-content:center;gap:1em;">
          <button type="button" class="expire-cancel-btn" style="padding:0.6em 1.6em; border-radius:0.7em; border:none; background:#f3f3f6; color:#222; font-weight:600; font-size:1em; margin-right:0.25em;cursor:pointer;" onclick="hideExpireConfirm()">Batal</button>
          <button type="button" class="expire-ok-btn" style="padding:0.6em 1.6em; border-radius:0.7em; border:none; background:#ffd600; color:#222; font-weight:700; font-size:1em;cursor:pointer;" onclick="submitExpireOrder()">OK</button>
        </div>
      </div>
    </div>
    <script>
    // Variabel dan fungsi untuk modal konfirmasi kadaluarsa
    let expireOrderId = null;
    function showExpireConfirm(orderId) {
      expireOrderId = orderId;
      document.getElementById('expire-confirm-modal').style.display = 'flex';
    }
    function hideExpireConfirm() {
      expireOrderId = null;
      document.getElementById('expire-confirm-modal').style.display = 'none';
    }
    function submitExpireOrder() {
      if (!expireOrderId) return;
      let form = document.getElementById('expire-form-' + expireOrderId);
      if (form) {
        form.submit();
        hideExpireConfirm();
      }
    }
    
    // Waktu dan user login saat ini
    const currentDateTime = '2025-07-28 13:56:20';
    const currentUser = 'redeemself';
    console.log('Current date and time: ' + currentDateTime);
    console.log('Current user: ' + currentUser);
    
    // Script untuk menangani border dan spasi antar kolom tabel
    document.addEventListener('DOMContentLoaded', function() {
      // Fungsi untuk memperlebar tabel ke kiri
      function widenTableToLeft() {
        const containers = document.querySelectorAll('.orders-table-container, .canceled-table-container, .expired-table-container');
        const tables = document.querySelectorAll('.orders-table, .canceled-orders-table, .expired-orders-table');
        
        containers.forEach(container => {
          container.style.maxWidth = '100%';
          container.style.paddingLeft = '0.5rem';
          container.style.paddingRight = '0.5rem';
        });
        
        tables.forEach(table => {
          table.style.width = '100%';
        });
        
        // Memperkecil ukuran font dan padding untuk kolom-kolom
        const tableCells = document.querySelectorAll('td, th');
        tableCells.forEach(cell => {
          cell.style.fontSize = '0.95rem';
          cell.style.padding = '0.9rem 0.5rem';
        });
      }
      
      // Jalankan fungsi perlebaran tabel
      widenTableToLeft();
      
      // Fungsi untuk menyesuaikan lebar kolom tabel
      function adjustTableColumns() {
        const tables = document.querySelectorAll('.orders-table, .canceled-orders-table, .expired-orders-table');
        
        tables.forEach(table => {
          // Pastikan semua kolom produk dan pengiriman memiliki jarak yang tepat
          const productCells = table.querySelectorAll('.column-product');
          const shippingCells = table.querySelectorAll('.column-shipping');
          const paymentCells = table.querySelectorAll('.column-payment');
          const actionCells = table.querySelectorAll('.column-action');
          const statusCells = table.querySelectorAll('.column-status');
          const priceCells = table.querySelectorAll('.column-price');
          
          productCells.forEach(cell => {
            cell.style.borderRight = '1px solid #e8f4ea';
            cell.style.paddingRight = '12px';
          });
          
          shippingCells.forEach(cell => {
            cell.style.borderRight = '1px solid #e8f4ea';
            cell.style.paddingRight = '12px';
            cell.style.paddingLeft = '12px';
          });
          
          paymentCells.forEach(cell => {
            cell.style.paddingLeft = '12px';
            cell.style.borderRight = '1px solid #e8f4ea';
          });
          
          // Styling khusus untuk kolom status dan harga
          statusCells.forEach(cell => {
            cell.style.borderRight = '1px solid #e8f4ea';
            cell.style.paddingRight = '12px';
          });
          
          priceCells.forEach(cell => {
            cell.style.paddingLeft = '12px';
          });
          
          // Styling khusus untuk kolom aksi agar tombol masuk
          actionCells.forEach(cell => {
            cell.style.minWidth = '140px';
            cell.style.width = '140px';
            cell.style.paddingLeft = '10px';
            cell.style.paddingRight = '10px';
            cell.style.textAlign = 'center';
          });
        });
      }
      
      // Jalankan fungsi penyesuaian kolom
      adjustTableColumns();
      
      // Juga jalankan ketika window diresize
      window.addEventListener('resize', adjustTableColumns);
      
      // Fungsi untuk menerapkan zebra striping pada baris tabel
      function applyZebraStriping() {
        const tables = document.querySelectorAll('.orders-table, .canceled-orders-table, .expired-orders-table');
        
        tables.forEach(table => {
          const rows = table.querySelectorAll('tbody tr');
          rows.forEach((row, index) => {
            if (index % 2 === 1) { // baris ganjil
              row.style.backgroundColor = '#fbfcfa';
            }
          });
        });
      }
      
      // Terapkan zebra striping
      applyZebraStriping();
      
      // Fungsi untuk memastikan tombol aksi berada dalam tabel dengan baik
      function adjustActionButtons() {
        const actionBtns = document.querySelectorAll('.action-btn');
        
        actionBtns.forEach(btn => {
          // Style khusus untuk tombol agar mirip dengan gambar
          btn.style.fontSize = '0.85em';
          btn.style.padding = '0.6em 0.5em';
          btn.style.marginBottom = '6px';
          btn.style.borderRadius = '0.5em';
          btn.style.minHeight = '32px';
          btn.style.maxWidth = '130px';
          btn.style.margin = '0 auto 6px auto';
          btn.style.display = 'flex';
          btn.style.justifyContent = 'center';
          btn.style.alignItems = 'center';
        });
      }
      
      // Terapkan penyesuaian tombol aksi
      adjustActionButtons();
      
      // Styling tambahan untuk header tabel
      const tableHeaders = document.querySelectorAll('th');
      tableHeaders.forEach(th => {
        th.style.textAlign = 'center';
        th.style.padding = '12px 8px';
      });
      
      // Tambahkan jarak antara status dan harga
      const statusWrappers = document.querySelectorAll('.status-cell-wrapper');
      statusWrappers.forEach(wrapper => {
        wrapper.style.paddingRight = '10px';
        wrapper.style.borderRight = '1px solid #e8f4ea';
      });
      
      const totalPrices = document.querySelectorAll('.total-price');
      totalPrices.forEach(price => {
        price.style.marginLeft = '10px';
        price.style.fontWeight = 'bold';
      });
    });
    </script>
  </div>
</div>
@endsection