<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesControllerErrors;
use App\Models\MaidBill;
use App\Models\User;
use App\Support\Month;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class MaidBillController extends Controller
{
    use HandlesControllerErrors;

    public function create(): View
    {
        return view('maid-bills.create', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function index(Request $request): JsonResponse|View
    {
        try {
            [$selectedMonth] = Month::normalize($request->input('month'));
            $start = $selectedMonth.'-01';

            $maidBills = MaidBill::query()
                ->with('user')
                ->where('month', $selectedMonth)
                ->orderByDesc('id')
                ->get();

            $totalMaid = (string) (MaidBill::query()
                ->where('month', $selectedMonth)
                ->selectRaw('COALESCE(ROUND(SUM(amount), 2), 0) as total_amount')
                ->value('total_amount') ?? '0.00');

            if ($request->wantsJson()) {
                return response()->json($maidBills);
            }

            return view('maid-bills.index', [
                'maidBills' => $maidBills,
                'totalMaid' => $totalMaid,
                'month' => $selectedMonth,
                'monthLabel' => date('F Y', strtotime($start)),
            ]);
        } catch (Throwable $e) {
            $this->logControllerError($e, 'maid_bills.index_failed', [
                'month' => $request->input('month'),
            ]);
            return $this->errorResponse($request, 'maid-bills.index');
        }
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'amount' => 'required|numeric|min:0',
                'month' => 'required|date_format:Y-m',
                'note' => 'nullable|string',
            ]);

            [$normalizedMonth] = Month::normalize($request->input('month'));
            if ($normalizedMonth !== $request->input('month')) {
                $this->logMissingData('maid_bills.invalid_month_input', [
                    'month' => $request->input('month'),
                ]);
                return redirect()->back()->withInput()->withErrors(['month' => 'Enter a valid month.']);
            }

            $maidBill = MaidBill::create([
                'user_id' => $request->user_id,
                'amount' => number_format((float) $request->amount, 2, '.', ''),
                'month' => $request->month,
                'note' => $request->note,
            ]);
            $maidBill->load('user');

            if ($request->wantsJson()) {
                return response()->json($maidBill, 201);
            }

            return redirect()->route('maid-bills.index', ['month' => $request->month])
                ->with('success', 'Maid bill recorded successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'maid_bills.insert_failed', [
                'user_id' => $request->input('user_id'),
                'month' => $request->input('month'),
            ]);
            return $this->errorResponse($request, 'maid-bills.index');
        }
    }

    public function edit(MaidBill $maidBill): View
    {
        return view('maid-bills.edit', [
            'maidBill' => $maidBill->load('user'),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, MaidBill $maidBill): JsonResponse|RedirectResponse
    {
        try {
            $request->validate([
                'user_id' => ['required', 'integer', 'exists:users,id'],
                'amount' => ['required', 'numeric', 'min:0'],
                'month' => ['required', 'date_format:Y-m'],
                'note' => ['nullable', 'string'],
            ]);

            [$normalizedMonth] = Month::normalize($request->input('month'));
            if ($normalizedMonth !== $request->input('month')) {
                $this->logMissingData('maid_bills.invalid_month_input', [
                    'id' => $maidBill->id,
                    'month' => $request->input('month'),
                ]);
                return redirect()->back()->withInput()->withErrors(['month' => 'Enter a valid month.']);
            }

            $maidBill->update([
                'user_id' => $request->user_id,
                'amount' => number_format((float) $request->amount, 2, '.', ''),
                'month' => $request->month,
                'note' => $request->note,
            ]);
            $maidBill->load('user');

            if ($request->wantsJson()) {
                return response()->json($maidBill->fresh(['user']));
            }

            return redirect()->route('maid-bills.index', ['month' => $request->month])
                ->with('success', 'Maid bill updated successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'maid_bills.update_failed', [
                'id' => $maidBill->id,
            ]);
            return $this->errorResponse($request, 'maid-bills.index');
        }
    }

    public function destroy(Request $request, MaidBill $maidBill): JsonResponse|RedirectResponse
    {
        try {
            $month = $maidBill->month;
            $maidBill->delete();

            if ($request->wantsJson()) {
                return response()->json(null, 204);
            }

            return redirect()->route('maid-bills.index', ['month' => $month])
                ->with('success', 'Maid bill deleted successfully.');
        } catch (Throwable $e) {
            $this->logControllerError($e, 'maid_bills.delete_failed', [
                'id' => $maidBill->id,
            ]);
            return $this->errorResponse($request, 'maid-bills.index');
        }
    }
}
