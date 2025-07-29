@extends('layouts.app')

@section('content')
<h1>Daftar Pesanan</h1>
<ul>
    @foreach ($orders as $order)
        <li>
            <a href="{{ route('user.orders.show', $order->id) }}">
                {{ $order->order_code ?? 'No. Pesanan' }}
            </a>
        </li>
    @endforeach
</ul>
@endsection
