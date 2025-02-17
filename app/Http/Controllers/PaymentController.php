<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();

        // Obtém os meses disponíveis para filtro
        $months = Payment::selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as month")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('month');

        // Define o mês e semana atuais
        $selectedMonth = $now->format('Y-m');
        $selectedWeek = $now->weekOfYear;

        return view('finances.index', compact('months', 'selectedMonth', 'selectedWeek'));
    }

    public function getTotals(Request $request)
    {
        $now = Carbon::now();

        // Obtém os valores do request ou usa valores padrão (mês e semana atual)
        $selectedMonth = $request->input('month', $now->format('Y-m'));
        $selectedWeek = $request->input('week', $now->weekOfYear);

        // Define os intervalos de mês e semana
        $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $startOfWeek = Carbon::now()->setISODate($startOfMonth->year, $selectedWeek)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        // Função para filtrar pagamentos por status de locação
        $getPaymentsByStatus = function ($statusQuery) {
            return Payment::whereHas('rental', function ($query) use ($statusQuery) {
                $statusQuery($query);
            });
        };

        // Filtros por categoria de aluguel
        $filters = [
            'em_andamento' => fn($q) => $q->whereNull('finished_at'),
        ];

        // Calculando os totais por categoria
        $totals = [];
        foreach ($filters as $status => $statusQuery) {
            $totals[$status] = [
                'total' => [
                    'received' => $getPaymentsByStatus($statusQuery)->whereNotNull('paid_in')->sum('cost'),
                    'not_received' => $getPaymentsByStatus($statusQuery)->whereNull('paid_in')->sum('cost'),
                ],
                'month' => [
                    'received' => $getPaymentsByStatus($statusQuery)->whereNotNull('paid_in')
                        ->whereBetween('paid_in', [$startOfMonth, $endOfMonth])->sum('cost'),
                    'not_received' => $getPaymentsByStatus($statusQuery)->whereNull('paid_in')
                        ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('cost'),
                ],
                'week' => [
                    'received' => $getPaymentsByStatus($statusQuery)->whereNotNull('paid_in')
                        ->whereBetween('paid_in', [$startOfWeek, $endOfWeek])->sum('cost'),
                    'not_received' => $getPaymentsByStatus($statusQuery)->whereNull('paid_in')
                        ->whereBetween('payment_date', [$startOfWeek, $endOfWeek])->sum('cost'),
                ]
            ];
        }

        return response()->json($totals);
    }

    public function getWeeks(Request $request)
    {
        $selectedMonth = $request->input('month');

        if (!$selectedMonth) {
            return response()->json([]);
        }

        // Obtém o primeiro e o último dia do mês selecionado
        $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Obtém as semanas dentro desse intervalo
        $weeks = [];
        $currentDate = $startOfMonth->copy();

        while ($currentDate->lessThanOrEqualTo($endOfMonth)) {
            $weeks[] = $currentDate->weekOfYear;
            $currentDate->addWeek();
        }

        return response()->json(array_values(array_unique($weeks)));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $vehicle, string $rental = null)
    {
        $paymentsQuery = Payment::query();

        if ($rental) {
            $paymentsQuery->whereHas('rental', function ($query) use ($vehicle, $rental) {
                $query->where('id', $rental)
                    ->where('vehicle_id', $vehicle);
            });
        } else {
            $paymentsQuery->whereHas('rental', function ($query) use ($vehicle) {
                $query->where('vehicle_id', $vehicle);
            });
        }

        $payments = $paymentsQuery->get();

        $payments->transform(function ($item, $index) {
            $item->count = $index + 1;
            return $item;
        });

        return response()->json($payments, 200);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Payment $payment)
    {
        $validate = $request->validate([
            'paid' => 'nullable|boolean'
        ]);

        if (isset($validate['paid']) && $validate['paid'] == 1) {
            $payment->paid = 1;
            $payment->paid_in = now();
            $returnMessage = 'Valor recebido!';
        } else {
            $payment->paid = 0;
            $payment->paid_in = null;
            $returnMessage = 'Valor não recebido.';
        }
        $payment->save();

        return response()->json(['success' => $returnMessage]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
