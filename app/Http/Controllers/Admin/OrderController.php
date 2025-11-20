<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user']);

        if ($search = $request->query('q')) {
            $query->where('id', $search)
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
        }

        $orders = $query->latest()->paginate(25)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.produk', 'payments']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Return unread/pending orders count for admin UI (JSON).
     */
    public function unreadCount()
    {
        $count = Order::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }
}
