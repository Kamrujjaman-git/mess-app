<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Models\MarketExpense;
use App\Models\Meal;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $month = request('month', date('Y-m'));

        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        [$year, $monthNum] = array_map('intval', explode('-', $month, 2));
        if (! checkdate($monthNum, 1, $year)) {
            $month = date('Y-m');
        }

        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));

        $totalExpense = round((float) MarketExpense::query()
            ->whereBetween('date', [$start, $end])
            ->sum('amount'), 2);

        $totalMeals = (int) (Meal::query()
            ->whereBetween('date', [$start, $end])
            ->selectRaw(
                'SUM(CASE WHEN lunch THEN 1 ELSE 0 END + CASE WHEN dinner THEN 1 ELSE 0 END) as meal_units'
            )
            ->value('meal_units') ?? 0);

        $costPerMealRatio = $totalMeals > 0 ? $totalExpense / $totalMeals : null;
        $costPerMeal = $costPerMealRatio !== null ? round($costPerMealRatio, 2) : null;

        $advancePaidByUserId = AdvancePayment::query()
            ->whereBetween('date', [$start, $end])
            ->selectRaw('user_id, SUM(amount) as total_advance')
            ->groupBy('user_id')
            ->pluck('total_advance', 'user_id');

        $mealsByUserId = Meal::query()
            ->whereBetween('date', [$start, $end])
            ->selectRaw(
                'user_id, SUM(CASE WHEN lunch THEN 1 ELSE 0 END + CASE WHEN dinner THEN 1 ELSE 0 END) as units'
            )
            ->groupBy('user_id')
            ->pluck('units', 'user_id');

        $perUserBalances = User::query()
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use ($costPerMealRatio, $advancePaidByUserId, $mealsByUserId) {
                $meals = (int) ($mealsByUserId[$user->id] ?? 0);
                $advancePaid = round((float) ($advancePaidByUserId[$user->id] ?? 0), 2);
                $cost = $costPerMealRatio !== null
                    ? round($meals * $costPerMealRatio, 2)
                    : 0.0;
                $balance = round($advancePaid - $cost, 2);

                return [
                    'name' => $user->name,
                    'meals' => $meals,
                    'advancePaid' => $advancePaid,
                    'cost' => $cost,
                    'balance' => $balance,
                ];
            });

        return view('dashboard', [
            'totalExpense' => $totalExpense,
            'totalMeals' => $totalMeals,
            'costPerMeal' => $costPerMeal,
            'perUserBalances' => $perUserBalances,
            'month' => $month,
            'monthLabel' => date('F Y', strtotime($start)),
        ]);
    }
}
