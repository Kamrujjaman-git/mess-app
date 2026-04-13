<?php

namespace App\Http\Controllers;

use App\Models\MarketExpense;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketExpenseController extends Controller
{
    public function create(): View
    {
        return view('market-expenses.create', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
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
        $month = $request->input('month', date('Y-m'));

        if (! is_string($month) || ! preg_match('/^\d{4}-\d{2}$/', $month)) {
            $month = date('Y-m');
        }

        [$year, $monthNum] = array_map('intval', explode('-', $month, 2));
        if (! checkdate($monthNum, 1, $year)) {
            $month = date('Y-m');
        }

        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));

        $expenses = MarketExpense::query()
            ->with('user')
            ->whereBetween('date', [$start, $end])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($expenses);
        }

        return view('expenses.index', [
            'expenses' => $expenses,
            'month' => $month,
            'monthLabel' => date('F Y', strtotime($start)),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        $expense = MarketExpense::create($validated);
        $expense->load('user');

        if ($request->wantsJson()) {
            return response()->json($expense, 201);
        }

        return redirect()->route('dashboard')->with('success', 'Expense recorded successfully.');
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
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        $marketExpense->update($validated);
        $marketExpense->load('user');

        if ($request->wantsJson()) {
            return response()->json($marketExpense->fresh(['user']));
        }

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, MarketExpense $marketExpense): JsonResponse|RedirectResponse
    {
        $marketExpense->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
