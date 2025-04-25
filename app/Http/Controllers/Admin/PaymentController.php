<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Payment::query()->with('user');
        
        // Aplicar filtros si existen
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('external_reference', 'like', "%{$search}%")
                  ->orWhere('preference_id', 'like', "%{$search}%")
                  ->orWhere('payment_id', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Ordenar por fecha descendente (más reciente primero)
        $query->orderBy('created_at', 'desc');
        
        // Paginar resultados
        $payments = $query->paginate(15)->withQueryString();
        
        return view('admin.payments.index', compact('payments'));
    }
    
    /**
     * Display the specified payment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $payment = Payment::with(['user'])->findOrFail($id);
        
        // Si tiene una relación polimórfica, cargarla
        if ($payment->related_id && $payment->related_type) {
            $payment->load('related');
        }
        
        return view('admin.payments.show', compact('payment'));
    }
    
    /**
     * Exportar pagos a CSV/Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = Payment::query()->with('user');
        
        // Aplicar los mismos filtros que en el index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('external_reference', 'like', "%{$search}%")
                  ->orWhere('preference_id', 'like', "%{$search}%")
                  ->orWhere('payment_id', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Ordenar por fecha descendente
        $query->orderBy('created_at', 'desc');
        
        // Obtener todos los pagos filtrados
        $payments = $query->get();
        
        // Generar CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="pagos_'.date('Y-m-d').'.csv"',
        ];
        
        $columns = [
            'ID', 'Fecha', 'Título', 'Monto', 'Moneda', 'Estado', 
            'Usuario', 'Email', 'Preference ID', 'Payment ID', 'Referencia Externa'
        ];
        
        $callback = function() use ($payments, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->created_at->format('d/m/Y H:i:s'),
                    $payment->title,
                    $payment->amount,
                    $payment->currency,
                    $payment->status,
                    optional($payment->user)->name,
                    optional($payment->user)->email,
                    $payment->preference_id,
                    $payment->payment_id,
                    $payment->external_reference
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Mostrar estadísticas/dashboard de pagos
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Estadísticas generales
        $stats = [
            'total' => Payment::count(),
            'approved' => Payment::where('status', 'approved')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
            'total_amount' => Payment::where('status', 'approved')->sum('amount'),
        ];
        
        // Pagos por mes (últimos 6 meses)
        $months = collect();
        for ($i = 0; $i < 6; $i++) {
            $date = now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'count' => Payment::whereYear('created_at', $date->year)
                                 ->whereMonth('created_at', $date->month)
                                 ->count(),
                'approved' => Payment::where('status', 'approved')
                                    ->whereYear('created_at', $date->year)
                                    ->whereMonth('created_at', $date->month)
                                    ->count(),
                'amount' => Payment::where('status', 'approved')
                                  ->whereYear('created_at', $date->year)
                                  ->whereMonth('created_at', $date->month)
                                  ->sum('amount')
            ]);
        }
        
        // Pagos recientes
        $recent = Payment::with('user')
                         ->orderBy('created_at', 'desc')
                         ->limit(5)
                         ->get();
        
        return view('admin.payments.dashboard', compact('stats', 'months', 'recent'));
    }
}