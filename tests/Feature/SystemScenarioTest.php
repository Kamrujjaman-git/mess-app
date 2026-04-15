<?php

namespace Tests\Feature;

use App\Models\AdvancePayment;
use App\Models\HouseRent;
use App\Models\MaidBill;
use App\Models\MarketExpense;
use App\Models\Meal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemScenarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_data_entry_flow_stores_expected_records(): void
    {
        $userPayload = [
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'role' => 'member',
        ];

        $this->post(route('users.store'), $userPayload)->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', $userPayload['email'])->firstOrFail();

        $this->post(route('meals.store'), [
            'user_id' => $user->id,
            'date' => '2026-04-10',
            'lunch' => '1',
            'dinner' => '1',
        ])->assertRedirect(route('dashboard'));

        $this->post(route('expenses.store'), [
            'user_id' => $user->id,
            'amount' => '300.00',
            'date' => '2026-04-10',
            'note' => 'Market test expense',
        ])->assertRedirect(route('dashboard'));

        $this->post(route('advance-payments.store'), [
            'user_id' => $user->id,
            'amount' => '500.00',
            'date' => '2026-04-10',
        ])->assertRedirect(route('advance-payments.index'));

        $this->post(route('house-rents.store'), [
            'user_id' => $user->id,
            'amount' => '100.00',
            'month' => '2026-04',
            'note' => 'Rent test',
        ])->assertRedirect(route('house-rents.index', ['month' => '2026-04']));

        $this->post(route('maid-bills.store'), [
            'user_id' => $user->id,
            'amount' => '50.00',
            'month' => '2026-04',
            'note' => 'Maid test',
        ])->assertRedirect(route('maid-bills.index', ['month' => '2026-04']));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'role' => 'member',
        ]);

        $this->assertDatabaseHas('meals', [
            'user_id' => $user->id,
            'date' => '2026-04-10 00:00:00',
            'lunch' => 1,
            'dinner' => 1,
        ]);

        $this->assertDatabaseHas('market_expenses', [
            'user_id' => $user->id,
            'amount' => 300.00,
            'date' => '2026-04-10 00:00:00',
        ]);

        $this->assertDatabaseHas('advance_payments', [
            'user_id' => $user->id,
            'amount' => 500.00,
            'date' => '2026-04-10 00:00:00',
        ]);

        $this->assertDatabaseHas('house_rents', [
            'user_id' => $user->id,
            'amount' => 100.00,
            'month' => '2026-04',
        ]);

        $this->assertDatabaseHas('maid_bills', [
            'user_id' => $user->id,
            'amount' => 50.00,
            'month' => '2026-04',
        ]);
    }

    public function test_dashboard_and_monthly_summary_calculations_are_correct(): void
    {
        $user = User::query()->create([
            'name' => 'Calc Member',
            'email' => 'calc@example.com',
            'role' => 'member',
        ]);

        Meal::query()->create([
            'user_id' => $user->id,
            'date' => '2026-04-11',
            'lunch' => true,
            'dinner' => true,
        ]);

        MarketExpense::query()->create([
            'user_id' => $user->id,
            'amount' => '300.00',
            'date' => '2026-04-11',
            'note' => 'Calculation expense',
        ]);

        AdvancePayment::query()->create([
            'user_id' => $user->id,
            'amount' => '500.00',
            'date' => '2026-04-11',
        ]);

        HouseRent::query()->create([
            'user_id' => $user->id,
            'amount' => '100.00',
            'month' => '2026-04',
            'note' => null,
        ]);

        MaidBill::query()->create([
            'user_id' => $user->id,
            'amount' => '50.00',
            'month' => '2026-04',
            'note' => null,
        ]);

        $dashboard = $this->get(route('dashboard', ['month' => '2026-04']));
        $dashboard->assertOk();
        $dashboard->assertViewHas('totalExpense', '300.00');
        $dashboard->assertViewHas('totalMeals', 2);
        $dashboard->assertViewHas('costPerMeal', '150.00');
        $dashboard->assertViewHas('perUserBalances', function ($rows) {
            if ($rows->count() !== 1) {
                return false;
            }

            $row = $rows->first();

            return $row['advancePaid'] === '500.00'
                && $row['cost'] === '300.00'
                && $row['balance'] === '200.00';
        });

        $summary = $this->get(route('monthly-summary.index', ['month' => '2026-04']));
        $summary->assertOk();
        $summary->assertViewHas('totalExpense', '300.00');
        $summary->assertViewHas('totalMeals', 2);
        $summary->assertViewHas('costPerMeal', '150.00');
        $summary->assertViewHas('totalAdvanceAll', '500.00');
        $summary->assertViewHas('totalMealCostAll', '300.00');
        $summary->assertViewHas('totalMealBalanceAll', '200.00');
        $summary->assertViewHas('totalRentAll', '100.00');
        $summary->assertViewHas('totalMaidAll', '50.00');
        $summary->assertViewHas('totalFinalBalanceAll', '50.00');
        $summary->assertViewHas('usersData', function ($rows) {
            if ($rows->count() !== 1) {
                return false;
            }

            $row = $rows->first();

            return $row['totalMeals'] === 2
                && $row['advancePaid'] === '500.00'
                && $row['mealCost'] === '300.00'
                && $row['mealBalance'] === '200.00'
                && $row['rent'] === '100.00'
                && $row['maid'] === '50.00'
                && $row['finalBalance'] === '50.00';
        });
    }
}

