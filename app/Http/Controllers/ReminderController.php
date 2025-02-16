<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function getNotifications()
    {
        $today = Carbon::today();
        $twoDaysAhead = $today->copy()->addDays(2);
        $oneDayAhead = $today->copy()->addDay(1);

        // Pagamentos a vencer e vencidos
        $notifications = [
            'hoje' => Payment::whereHas('actualRental')->where('payment_date', $today)->where('paid', 0)->with(['rental' => function ($query) {
                $query->with('vehicle');
            }])->get(),
            'faltam_1_dia' => Payment::whereHas('actualRental')->where('payment_date', $oneDayAhead)->where('paid', 0)->with(['rental' => function ($query) {
                $query->with('vehicle');
            }])->get(),
            'faltam_2_dias' => Payment::whereHas('actualRental')->where('payment_date', $twoDaysAhead)->where('paid', 0)->with(['rental' => function ($query) {
                $query->with('vehicle');
            }])->get(),
            'vencidos' => Payment::whereHas('actualRental')->where('payment_date', '<', $today)->with(['rental' => function ($query) {
                $query->with('vehicle');
            }])->where('paid', 0)->get(),
            'quinta_feira' => [
                'mensagem' => 'Lembre-se de revisar as finanças!',
                'data' => $this->getUpcomingThursday(),
            ]
        ];

        // Buscando veículos para trocas de óleo e revisões
        $vehicles = Vehicle::with(['latestMaintenance', 'latestOilChange'])->whereHas('actualRental')->get();

        $oilChanges = [];
        $revisions = [];

        foreach ($vehicles as $vehicle) {
            $actualKm = $vehicle->actual_km;

            // Cálculo da revisão
            $latestMaintenanceKm = $vehicle->latestMaintenance->actual_km ?? $vehicle->first_declared_km;
            $nextMaintenanceKm = $vehicle->next_revision;
            $kmRemainingMaintenance = $nextMaintenanceKm - $actualKm;

            // Cálculo da troca de óleo
            $latestOilChangeKm = $vehicle->latestOilChange->actual_km ?? $vehicle->first_declared_km;
            $nextOilChangeKm = $vehicle->next_oil_change;
            $oilKmRemaining = $nextOilChangeKm - $actualKm;

            // Se está na faixa de 0 a 500 km ou já passou, adiciona ao alerta
            if ($kmRemainingMaintenance <= 1000) {
                $revisions[] = [
                    'veiculo' => $vehicle->brand . ' ' . $vehicle->model,
                    'placa' => $vehicle->license_plate,
                    'km_restante' => $kmRemainingMaintenance,
                    'person' => $vehicle->actualRental->landlord_name,
                    'rental' => $vehicle->actualRental->id
                ];
            }

            if ($oilKmRemaining <= 1000) {
                $oilChanges[] = [
                    'veiculo' => $vehicle->brand . ' ' . $vehicle->model,
                    'placa' => $vehicle->license_plate,
                    'km_restante' => $oilKmRemaining,
                    'person' => $vehicle->actualRental->landlord_name,
                    'rental' => $vehicle->actualRental->id
                ];
            }
        }

        return view('notifications.index', compact('notifications', 'oilChanges', 'revisions'));
    }

    public function getNotificationsCount()
    {
        $today = Carbon::today();
        $oneDayAhead = $today->copy()->addDay(1);
        $twoDaysAhead = $today->copy()->addDays(2);

        $paymentsToday = Payment::whereHas('actualRental')->where('payment_date', $today)->where('paid', 0)->count();
        $paymentsOneDay = Payment::whereHas('actualRental')->where('payment_date', $oneDayAhead)->where('paid', 0)->count();
        $paymentsTwoDays = Payment::whereHas('actualRental')->where('payment_date', $twoDaysAhead)->where('paid', 0)->count();
        $overduePayments = Payment::whereHas('actualRental')->where('payment_date', '<', $today)->where('paid', 0)->count();

        // Buscar veículos para trocas de óleo e revisões
        $vehicles = Vehicle::with(['latestMaintenance', 'latestOilChange'])->whereHas('actualRental')->get();

        $oilChangesCount = 0;
        $revisionsCount = 0;

        foreach ($vehicles as $vehicle) {
            $actualKm = $vehicle->actual_km;

            $nextMaintenanceKm = $vehicle->next_revision;
            $kmRemainingMaintenance = $nextMaintenanceKm - $actualKm;

            // Cálculo da troca de óleo
            $nextOilChangeKm = $vehicle->next_oil_change;
            $oilKmRemaining = $nextOilChangeKm - $actualKm;

            if ($kmRemainingMaintenance <= 1000) {
                $revisionsCount++;
            }
            if ($oilKmRemaining <= 1000) {
                $oilChangesCount++;
            }
        }

        // Notificação especial de quinta-feira
        $isThursday = Carbon::today()->isMonday();

        // Retornar as notificações
        $totalNotifications = $paymentsToday + $paymentsOneDay + $paymentsTwoDays + $overduePayments + $oilChangesCount + $revisionsCount;

        return response()->json([
            'total' => $totalNotifications,
            'quinta_feira' => $isThursday,
        ]);
    }


    private function getUpcomingThursday()
    {
        $today = Carbon::today();
        return $today->isMonday() ? true : false;
    }
}
