<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    
    // Get All Games 
    public function index(Request $request)
    {
        $query = Game::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('location', 'like', "%{$search}%")
                ->orWhere('date', 'like', "%{$search}%")
                ->orWhereHas('team1', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('team2', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        $games = $query->paginate(10); 
        return response()->json($games, 200);
    }






    // Show a specific Game by ID
    public function show($id)
    {
        $game = Game::with(['team1', 'team2'])->find($id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        return response()->json($game, 200);
    }






    // Create a new Game
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'score' => 'nullable|string|max:255',
            'result' => 'nullable|string|max:255',
            'team1_id' => 'required|exists:teams,id',
            'team2_id' => 'required|exists:teams,id',
            'win_team_id' => 'nullable|exists:teams,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create a new game
        $game = Game::create($request->all());
        return response()->json($game, 201);
    }






    // Update an existing Game
    public function update(Request $request, $id)
    {
        $game = Game::find($id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'score' => 'nullable|string|max:255',
            'result' => 'nullable|string|max:255',
            'team1_id' => 'nullable|exists:teams,id',
            'team2_id' => 'nullable|exists:teams,id',
            'win_team_id' => 'nullable|exists:teams,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update game details
        $game->update($request->all());
        return response()->json($game, 200);
    }






    
    // Delete a Game
    public function destroy($id)
    {
        $game = Game::find($id);

        if (!$game) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $game->delete();
        return response()->json(['message' => 'Game deleted successfully'], 200);
    }
}
