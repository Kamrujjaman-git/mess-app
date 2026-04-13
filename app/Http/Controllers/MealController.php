<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MealController extends Controller
{
    public function create(): View
    {
        return view('meals.create', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function edit(Meal $meal): View
    {
        return view('meals.edit', [
            'meal' => $meal->load('user'),
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

        $meals = Meal::query()
            ->with('user')
            ->whereBetween('date', [$start, $end])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($meals);
        }

        return view('meals.index', [
            'meals' => $meals,
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
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('meals', 'user_id')->where(
                    fn ($query) => $query->where('date', $request->input('date'))
                ),
            ],
            'date' => ['required', 'date'],
            'lunch' => ['sometimes', 'boolean'],
            'dinner' => ['sometimes', 'boolean'],
        ]);

        $meal = Meal::create([
            'user_id' => $validated['user_id'],
            'date' => $validated['date'],
            'lunch' => $request->boolean('lunch'),
            'dinner' => $request->boolean('dinner'),
        ]);
        $meal->load('user');

        if ($request->wantsJson()) {
            return response()->json($meal, 201);
        }

        return redirect()->route('dashboard')->with('success', 'Meal entry saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Meal $meal): JsonResponse
    {
        $meal->load('user');

        return response()->json($meal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meal $meal): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('meals', 'user_id')->where(
                    fn ($query) => $query->where('date', $request->input('date'))
                )->ignore($meal->id),
            ],
            'date' => ['required', 'date'],
            'lunch' => ['sometimes', 'boolean'],
            'dinner' => ['sometimes', 'boolean'],
        ]);

        $meal->update([
            'user_id' => $validated['user_id'],
            'date' => $validated['date'],
            'lunch' => $request->boolean('lunch'),
            'dinner' => $request->boolean('dinner'),
        ]);
        $meal->load('user');

        if ($request->wantsJson()) {
            return response()->json($meal->fresh(['user']));
        }

        return redirect()->route('meals.index')->with('success', 'Meal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Meal $meal): JsonResponse|RedirectResponse
    {
        $meal->delete();

        if ($request->wantsJson()) {
            return response()->json(null, 204);
        }

        return redirect()->route('meals.index')->with('success', 'Meal deleted successfully.');
    }
}
