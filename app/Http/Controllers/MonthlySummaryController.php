<?php

namespace App\Http\Controllers;

use App\Models\AdvancePayment;
use App\Models\HouseRent;
use App\Models\MaidBill;
use App\Models\MarketExpense;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonthlySummaryController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->input('month', date('Y-m'));

        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        [$year, $monthNum] = array_map('intval', explode('-', $month, 2));
        if (! checkdate($monthNum, 1, $year)) {
            $month = date('Y-m');
        }

        $startDate = $month.'-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $totalExpense = round((float) MarketExpense::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount'), 2);

        $totalMeals = (int) (Meal::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw(
                'SUM(CASE WHEN lunch THEN 1 ELSE 0 END + CASE WHEN dinner THEN 1 ELSE 0 END) as meal_units'
            )
            ->value('meal_units') ?? 0);

        $costPerMealRatio = $totalMeals > 0 ? $totalExpense / $totalMeals : null;
        $costPerMeal = $costPerMealRatio !== null ? round($costPerMealRatio, 2) : null;

        $advancePaidByUserId = AdvancePayment::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('user_id, SUM(amount) as total_advance')
            ->groupBy('user_id')
            ->pluck('total_advance', 'user_id');

        $mealsByUserId = Meal::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw(
                'user_id, SUM(CASE WHEN lunch THEN 1 ELSE 0 END + CASE WHEN dinner THEN 1 ELSE 0 END) as units'
            )
            ->groupBy('user_id')
            ->pluck('units', 'user_id');

        $rentByUserId = HouseRent::query()
            ->where('month', $month)
            ->selectRaw('user_id, SUM(amount) as total_rent')
            ->groupBy('user_id')
            ->pluck('total_rent', 'user_id');

        $maidByUserId = MaidBill::query()
            ->where('month', $month)
            ->selectRaw('user_id, SUM(amount) as total_maid')
            ->groupBy('user_id')
            ->pluck('total_maid', 'user_id');

        $usersData = User::query()
            ->orderBy('name')
            ->get()
            ->map(function (User $user) use (
                $costPerMealRatio,
                $advancePaidByUserId,
                $mealsByUserId,
                $rentByUserId,
                $maidByUserId
            ) {
                $userMeals = (int) ($mealsByUserId[$user->id] ?? 0);
                $advancePaid = round((float) ($advancePaidByUserId[$user->id] ?? 0), 2);
                $mealCost = $costPerMealRatio !== null
                    ? round($userMeals * $costPerMealRatio, 2)
                    : 0.0;
                $mealBalance = round($advancePaid - $mealCost, 2);

                $rent = round((float) ($rentByUserId[$user->id] ?? 0), 2);
                $maid = round((float) ($maidByUserId[$user->id] ?? 0), 2);

                $finalBalance = round($mealBalance - $rent - $maid, 2);

                return [
                    'name' => $user->name,
                    'totalMeals' => $userMeals,
                    'advancePaid' => $advancePaid,
                    'mealCost' => $mealCost,
                    'mealBalance' => $mealBalance,
                    'rent' => $rent,
                    'maid' => $maid,
                    'finalBalance' => $finalBalance,
                ];
            });

        return view('monthly-summary.index', [
            'totalExpense' => $totalExpense,
            'totalMeals' => $totalMeals,
            'costPerMeal' => $costPerMeal,
            'usersData' => $usersData,
            'month' => $month,
            'monthLabel' => date('F Y', strtotime($startDate)),
        ]);
    }
}
