<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesControllerErrors;
use App\Models\MarketExpense;
use App\Models\User;
use App\Support\Month;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class MarketExpenseController extends Controller
{
    use HandlesControllerErrors;

    public function create(): View
    {
        $users = User::query()->orderBy('name')->get();
        if ($users->isEmpty()) {
            $this->logMissingData('market_expenses.create_missing_users');
        }

        return view('market-expenses.create', ['users' => $users]);
    }

    public function edit(MarketExpense $marketExpense): View
    {
        return view('market-expenses.edit', [
            'expense' => $marketExpense->load('user'),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse|View
    {
        try {
            [$month, $year, $monthNum] = Month::normalize($request->input('month'));

            $expenses = MarketExpense::query()
                ->with('user')
                ->forMonth($year, $monthNum)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get();

            if ($request->wantsJson()) {
                return response()->json($expenses);
            }

            return view('expenses.index', [
                'expenses' => $expenses,
                'month' => $month,
                'monthLabel' => date('F Y', strtotime($month.'-01')),
            ]);
        } catch (Throwable $e) {
            $this->logControllerError($e, 'market_expenses.index_failed', [
                'month' => $request->input('month'),
            ]);
            return $this->errorResponse($request, 'expenses.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'date' => ['required', 'date_format:Y-m-d'],
                'note' => ['nullable', 'string'],
            ]);

            $expense = MarketExpense::create([
                'user_id' => $request->user_id,
                'amount' => number_format((float) $request->amount, 2, '.', ''),
                'date' => $request->date,
                'note' => $request->note,
            ]);
            $expense->load('user');

            if ($request->wantsJson()) {
                return response()->json($expense, 201);
            }

            return redirect()->route('dashboard')->with('success', 'Expense recorded successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'market_expenses.insert_failed', [
                'user_id' => $request->input('user_id'),
                'date' => $request->input('date'),
            ]);
            return $this->errorResponse($request, 'expenses.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MarketExpense $marketExpense): JsonResponse
    {
        $marketExpense->load('user');

        return response()->json($marketExpense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarketExpense $marketExpense): JsonResponse|RedirectResponse
    {
        try {
            $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'date' => ['required', 'date_format:Y-m-d'],
                'note' => ['nullable', 'string'],
            ]);

            $marketExpense->update([
                'user_id' => $request->user_id,
                'amount' => number_format((float) $request->amount, 2, '.', ''),
                'date' => $request->date,
                'note' => $request->note,
            ]);
            $marketExpense->load('user');

            if ($request->wantsJson()) {
                return response()->json($marketExpense->fresh(['user']));
            }

            return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'market_expenses.update_failed', [
                'id' => $marketExpense->id,
            ]);
            return $this->errorResponse($request, 'expenses.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, MarketExpense $marketExpense): JsonResponse|RedirectResponse
    {
        try {
            $marketExpense->delete();

            if ($request->wantsJson()) {
                return response()->json(null, 204);
            }

            return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'market_expenses.delete_failed', [
                'id' => $marketExpense->id,
            ]);
            return $this->errorResponse($request, 'expenses.index');
        }
    }
}
