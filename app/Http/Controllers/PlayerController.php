<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



class PlayerController extends Controller
{
    // Get All Players
    public function index(Request $request)
    {
        $query = Player::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('f_name', 'like', '%' . $search . '%')
                ->orWhere('l_name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('position') && !empty($request->position)) {
            $query->where('position', $request->position);
        }

        if ($request->has('team_id') && !empty($request->team_id)) {
            $query->where('team_id', $request->team_id);
        }

        $perPage = $request->get('per_page', 10);  
        $players = $query->paginate($perPage);

        return response()->json($players, 200);
    }



    // Create a new Player
    public function store(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email|unique:players,email',
            'position' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',
            'weight' => 'nullable|integer',
            'age' => 'nullable|integer|min:0',
            'experience' => 'nullable|integer|min:0',
            'college' => 'nullable|string|max:255',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png|max:1024', // Max 1 MB for avatar
            'team_id' => 'nullable|exists:teams,id' // Must reference an existing team
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filePath = $file->store('PlayerImage', 'public'); 
            $avatarPath = Storage::url($filePath); 
        }

        // Create player
        $player = Player::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'position' => $request->position,
            'height' => $request->height,
            'weight' => $request->weight,
            'age' => $request->age,
            'experience' => $request->experience,
            'college' => $request->college,
            'avatar' => $avatarPath,
            'team_id' => $request->team_id,
        ]);

        return response()->json($player, 201);
    }



    // Show a specific Player by ID
    public function show($id)
    {
        $player = Player::with('team')->find($id); 

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player, 200);
    }




    // Update an existing Player
    public function update(Request $request, $id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'f_name' => 'nullable|string|max:255',
            'l_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:players,email,' . $player->id,
            'position' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',
            'weight' => 'nullable|integer',
            'age' => 'nullable|integer|min:0',
            'experience' => 'nullable|integer|min:0',
            'college' => 'nullable|string|max:255',
            'avatar' => 'nullable|file|mimes:jpg,jpeg,png|max:1024', 
            'team_id' => 'nullable|exists:teams,id' 
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()){
            // Delete old avatar if it exists
            if ($player->avatar) {
                $oldLogoPath = public_path(str_replace('/storage', 'storage', $player->avatar));
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath); // Delete old logo file
                }
            }

            // Store new avatar
            $file = $request->file('avatar');
            $filePath = $file->store('PlayerImage', 'public'); 
            $player->avatar = Storage::url($filePath);  
        }

        // Update player details
        $player->f_name = $request->f_name ?? $player->f_name;
        $player->l_name = $request->l_name ?? $player->l_name;
        $player->email = $request->email ?? $player->email;
        $player->position = $request->position ?? $player->position;
        $player->height = $request->height ?? $player->height;
        $player->weight = $request->weight ?? $player->weight;
        $player->age = $request->age ?? $player->age;
        $player->experience = $request->experience ?? $player->experience;
        $player->college = $request->college ?? $player->college;
        $player->team_id = $request->team_id ?? $player->team_id;

        $player->save();

        return response()->json($player, 200);
    }





    // Delete a Player
    public function destroy($id)
    {
        $player = Player::find($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        // Delete avatar if exists
        if ($player->avatar) {
            $logoPath = public_path(str_replace('/storage', 'storage', $player->avatar));
            if (file_exists($logoPath)) {
                unlink($logoPath); // Delete the logo file
            }
        }

        $player->delete();

        return response()->json(['message' => 'Player deleted successfully'], 200);
    }
}
