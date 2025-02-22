<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LifeGoalController extends Controller
{
    /**
     * Display a listing of life goals
     */
    public function index()
    {
        $lifeGoals = Goal::where('user_id', Auth::id())->paginate(10);

        return view('pages.life_goals.index',[
            'title' => 'Life Goals | CashFlow',
            'lifeGoals' => $lifeGoals,
        ]);
    }

    /**
     * Show the form for creating a new life goal
     */
    public function create()
    {
        return view('pages.life_goals.create');
    }

    /**
     * Store a newly created life goal
     */
    public function store(Request $request)
    {
        try {
            $request->merge([
                'current_amount' => str_replace(['.', ','], '', $request->current_amount),
                'target_amount' => str_replace(['.', ','], '', $request->target_amount),
            ]);
            
            $validated = $request->validate([
                'goal_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'current_amount' => 'nullable|numeric|min:0',
                'target_amount' => 'required|numeric|min:0',
                'deadline' => 'required|date',
            ]);

            $validated['user_id'] = Auth::id();

            Goal::create($validated);

            return redirect()->route('web.app.life.goals.index')
                ->withToastSuccess( 'Life goal created successfully.');

        } catch (ValidationException $e) {
            Log::error('Validation error while creating life goal: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Error creating life goal: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while creating the life goal.')
                ->withInput();
        }
    }

    /**
     * Display the specified life goal
     */
    public function show(Goal $lifeGoal)
    {
        return view('app.life-goals.show', compact('lifeGoal'));
    }

    /**
     * Show the form for editing the life goal
     */
    public function edit(Goal $lifeGoal)
    {
        $lifeGoal = Goal::findOrFail( $lifeGoal->id );

        return view('pages.life_goals.edit', [
            'title' => 'Edit Life Goal | CashFlow',
            'lifeGoal' => $lifeGoal,
        ]);
    }

    /**
     * Update the specified life goal
     */
    public function update(Request $request, Goal $lifeGoal)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'target_amount' => 'required|numeric|min:0',
                'target_date' => 'required|date',
            ]);

            $lifeGoal->update($validated);

            return redirect()->route('life-goals.index')
                ->with('toast_success', 'Life goal updated successfully.');

        } catch (ValidationException $e) {
            Log::error('Validation error while updating life goal: ' . json_encode($e->errors()));
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            Log::error('Error updating life goal: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while updating the life goal.')
                ->withInput();
        }
    }

    /**
     * Remove the specified life goal
     */
    public function destroy(Goal $lifeGoal) 
    {
        try {
            $lifeGoal = Goal::findOrFail($lifeGoal->id);

            $lifeGoal->delete();

            return redirect()->route('web.app.life.goals.index')
                ->withToastSuccess('Life goal deleted successfully.');

        } catch (Exception $e) {
            Log::error('Error deleting life goal: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the life goal.');
        }
    }
}
