<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load(['items.produk.harga','payments']);
        return view('user.invoice', compact('order'));
    }

    public function download(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load(['items.produk.harga','payments']);

        // If barryvdh/laravel-dompdf is installed, use it to generate PDF
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class) || class_exists(\Dompdf\Dompdf::class)) {
            try {
                if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.invoice', compact('order'));
                    return $pdf->download('invoice-'.$order->id.'.pdf');
                }
                // fallback: render HTML and return as download (browser can save as PDF)
            } catch (\Throwable $e) {
                // fallthrough to HTML print
            }
        }

        // If PDF library not available, return printable HTML with auto print option
        return view('user.invoice', ['order' => $order, 'autoPrint' => true]);
    }
}
