<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    public function index()
    {
        // Retrieve all teams
        $teams = Team::all();
        return response()->json($teams);
    }




    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'result' => 'nullable|string|in:W,L',
            'score' => 'nullable|string|max:10',
            'logo' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create a new team
        $team = Team::create($request->all());
        return response()->json($team, 201);
    }




    public function show($id)
    {
        // Retrieve a specific team
        $team = Team::find($id);
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }
        return response()->json($team);
    }




    public function update(Request $request, $id)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'result' => 'nullable|string|in:W,L',
            'score' => 'nullable|string|max:10',
            'logo' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the team and update
        $team = Team::find($id);
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->update($request->all());
        return response()->json($team);
    }



    
    public function destroy($id)
    {
        // Find the team and delete
        $team = Team::find($id);
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->delete();
        return response()->json(['message' => 'Team deleted successfully']);
    }
}
