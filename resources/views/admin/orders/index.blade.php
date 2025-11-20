@extends('layouts.app')
@section('title', 'Orders')
@section('content')
<div class="container py-5">
    <h1 class="mb-4">Orders</h1>

    <form class="mb-3" method="get">
        <div class="input-group">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari by order id, user name or email">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>
                                {{ optional($order->user)->name }}<br>
                                <small class="text-muted">{{ optional($order->user)->email }}</small>
                            </td>
                            <td>Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</td>
                            <td>{{ $order->status ?? '—' }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
