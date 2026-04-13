<?php

namespace App\Http\Controllers;

use App\Models\MaidBill;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaidBillController extends Controller
{
    public function create(): View
    {
        return view('maid-bills.create', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function index(Request $request): JsonResponse|View
    {
        $selectedMonth = request('month', date('Y-m'));

        if (! is_string($selectedMonth) || ! preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = date('Y-m');
        }

        [$year, $monthNum] = array_map('intval', explode('-', $selectedMonth, 2));
        if (! checkdate($monthNum, 1, $year)) {
            $selectedMonth = date('Y-m');
        }

        $start = $selectedMonth.'-01';

        $maidBills = MaidBill::query()
            ->with('user')
            ->where('month', $selectedMonth)
            ->orderByDesc('id')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($maidBills);
        }

        return view('maid-bills.index', [
            'maidBills' => $maidBills,
            'month' => $selectedMonth,
            'monthLabel' => date('F Y', strtotime($start)),
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'month' => ['required', 'date_format:Y-m'],
            'note' => ['nullable', 'string'],
        ]);

        [$y, $m] = array_map('intval', explode('-', $validated['month'], 2));
        if (! checkdate($m, 1, $y)) {
            return redirect()->back()->withInput()->withErrors(['month' => 'Enter a valid month.']);
        }

        $maidBill = MaidBill::create($validated);
        $maidBill->load('user');

        if ($request->wantsJson()) {
            return response()->json($maidBill, 201);
        }

        return redirect()->route('maid-bills.index', ['month' => $validated['month']])
            ->with('success', 'Maid bill recorded successfully.');
    }
}
