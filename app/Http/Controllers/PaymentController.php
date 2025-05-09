<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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

        // Define o mês e a **semana correta**
        $selectedMonth = $now->format('Y-m');
        $selectedWeek = $now->copy()->weekOfYear; // Pegamos a semana atual correta

        // Define o início e fim da **semana atual**
        $startOfWeek = $now->copy()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SATURDAY);

        // Recupera pagamentos **com rental** dentro da semana
        $payments = Payment::with('rental:id,landlord_name')
            ->whereHas('rental', function ($query) {
                $query->whereNull('finished_at');
            })
            ->whereBetween('payment_date', [$startOfWeek, $endOfWeek])
            ->get();

        // Estrutura inicial para a semana
        $weekData = collect([
            'Sunday'    => ['day' => 'Domingo', 'vencidos' => 0, 'pendentes' => 0, 'pagos' => 0, 'total' => 0, 'rentals' => ['pendentes' => [], 'pagos' => [], 'vencidos' => []]],
            'Monday'    => ['day' => 'Segunda-feira', 'vencidos' => 0, 'pendentes' => 0, 'pagos' => 0, 'total' => 0, 'rentals' => ['pendentes' => [], 'pagos' => [], 'vencidos' => []]],
            'Tuesday'   => ['day' => 'Terça-feira', 'vencidos' => 0, 'pendentes' => 0, 'pagos' => 0, 'total' => 0, 'rentals' => ['pendentes' => [], 'pagos' => [], 'vencidos' => []]],
            'Wednesday' => ['day' => 'Quarta-feira', 'vencidos' => 0, 'pendentes' => 0, 'pagos' => 0, 'total' => 0, 'rentals' => ['pendentes' => [], 'pagos' => [], 'vencidos' => []]],
            'Thursday'  => ['day' => 'Quinta-feira', 'vencidos' => 0, 'pendentes' => 0, 'pagos' => 0, 'total' => 0, 'rentals' => ['pendentes' => [], 'pagos' => [], 'vencidos' => []]],
            'Friday'    => ['day' => 'Sexta-feira', 'vencidos' => 0, 'pendentes' => 0, 'pagos' => 0, 'total' => 0, 'rentals' => ['pendentes' => [], 'pagos' => [], 'vencidos' => []]],
            'Saturday'  => ['day' => 'Sábado', 'vencidos' => 0, 'pendentes' => 0, 'pagos' => 0, 'total' => 0, 'rentals' => ['pendentes' => [], 'pagos' => [], 'vencidos' => []]],
        ]);

        // Processa pagamentos e associa os rentals corretamente
        foreach ($payments as $payment) {
            $dayOfWeek = Carbon::parse($payment->payment_date)->format('l');

            if (!$weekData->has($dayOfWeek)) {
                continue;
            }

            $dayData = $weekData[$dayOfWeek];

            // Define se é um pagamento pendente, vencido ou pago
            if ($payment->paid) {
                $dayData['pagos']++;
            } elseif (Carbon::parse($payment->payment_date)->isPast()) {
                $dayData['vencidos']++;
            } else {
                $dayData['pendentes']++;
            }

            $dayData['total']++;

            // Se o pagamento está associado a um aluguel, categorizamos corretamente
            if ($payment->rental) {
                $rentalData = [
                    'id' => $payment->rental->id,
                    'name' => $payment->rental->landlord_name,
                    'cost' => $payment->cost,
                ];

                if (!$payment->paid && Carbon::parse($payment->payment_date)->isPast()) {
                    $dayData['rentals']['vencidos'][] = $rentalData;
                } elseif (!$payment->paid) {
                    $dayData['rentals']['pendentes'][] = $rentalData;
                } else {
                    $dayData['rentals']['pagos'][] = $rentalData;
                }
            }

            $weekData->put($dayOfWeek, $dayData);
        }

        return view('finances.index', compact('months', 'startOfWeek', 'endOfWeek', 'weekData', 'selectedMonth', 'selectedWeek'));
    }

    public function getTotals(Request $request)
    {
        $now = Carbon::now();

        // Obtém os valores do request ou usa valores padrão (mês e semana atual)
        $selectedMonth = $request->input('month', $now->format('Y-m'));
        $selectedWeek = $request->input('week', $now->weekOfYear);

        // Cria uma chave única para o cache com base em mês e semana
        $cacheKey = "totals_{$selectedMonth}_week_{$selectedWeek}";

        // Tenta recuperar os totais da cache; se não existir, calcula e armazena
        $totals = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($selectedMonth, $selectedWeek, $now) {
            // Define os intervalos de mês e semana
            $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $startOfWeek = Carbon::now()->setISODate($startOfMonth->year, $selectedWeek)->startOfWeek(Carbon::SUNDAY);
            $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SATURDAY);

            // Função para filtrar pagamentos por status de locação
            $getPaymentsByStatus = function ($statusQuery) {
                return Payment::whereHas('rental', function ($query) use ($statusQuery) {
                    $query->whereNull('finished_at');
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
                        'received' => Payment::where('paid', 1)->sum('cost'),
                        'not_received' => Payment::where('paid', 0)->sum('cost'),
                    ],
                    'month' => [
                        'received' => $getPaymentsByStatus($statusQuery)->where('paid', 1)
                            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('cost'),
                        'not_received' => $getPaymentsByStatus($statusQuery)->where('paid', 0)
                            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('cost'),
                    ],
                    'week' => [
                        'received' => $getPaymentsByStatus($statusQuery)->where('paid', 1)
                            ->whereBetween('payment_date', [$startOfWeek, $endOfWeek])->sum('cost'),
                        'not_received' => $getPaymentsByStatus($statusQuery)->where('paid', 0)
                            ->whereBetween('payment_date', [$startOfWeek, $endOfWeek])->sum('cost'),
                    ]
                ];
            }

            return $totals;
        });

        return response()->json($totals);
    }


    public function getWeeks(Request $request)
    {
        $selectedMonth = $request->input('month');

        if (!$selectedMonth) {
            return response()->json([]);
        }

        // Cria uma chave única para o cache com base no mês selecionado
        $cacheKey = "weeks_{$selectedMonth}";

        // Tenta recuperar as semanas da cache; se não existir, calcula e armazena
        $weeks = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($selectedMonth) {
            $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            $weeks = [];
            $currentDate = $startOfMonth->copy();

            while ($currentDate->lessThanOrEqualTo($endOfMonth)) {
                $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::SUNDAY);
                $endOfWeek = $currentDate->copy()->endOfWeek(Carbon::SATURDAY);

                if ($startOfWeek->month === $startOfMonth->month) {
                    $weeks[] = [
                        'week' => $currentDate->weekOfYear,
                        'range' => 'de ' . $startOfWeek->format('d/m') . ' a ' . $endOfWeek->format('d/m')
                    ];
                }
                $currentDate->addWeek();
            }

            return $weeks;
        });

        return response()->json($weeks);
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

        // 🔄 Limpar caches específicos
        $months = Payment::selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as month")
            ->groupBy('month')
            ->pluck('month');

        foreach ($months as $month) {
            for ($week = 1; $week <= 53; $week++) {
                // Limpa cache de getTotals para cada combinação de mês e semana
                Cache::forget("totals_{$month}_week_{$week}");
            }

            // Limpa cache de getWeeks para cada mês
            Cache::forget("weeks_{$month}");
        }

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
