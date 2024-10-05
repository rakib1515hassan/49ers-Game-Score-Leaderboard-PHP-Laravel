<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class TeamController extends Controller
{
    // Retrieve all Teams
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }


    
    // Create Team
    public function store(Request $request)
    {

        Log::info($request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:1024', // Max 1MB
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle file upload
        $logoPath = null;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $file = $request->file('logo');
            $filePath = $file->store('TeamLogo', 'public'); 

            $logoPath = Storage::url($filePath); 
        }

        // Create a new team
        $team = Team::create([
            'name' => $request->name,
            'title' => $request->title,
            'logo' => $logoPath,
        ]);

        return response()->json($team, 201);
    }



    // Retrieve a specific Team
    public function show($id)
    {
        $team = Team::find($id);
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }
        return response()->json($team);
    }




    // Update Team
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:1024', // Max 1MB
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->name = $request->name;
        $team->title = $request->title;

        // Handle optional logo update
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            // Delete the old logo if it exists
            if ($team->logo) {
                $oldLogoPath = public_path(str_replace('/storage', 'storage', $team->logo));
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath); // Delete old logo file
                }
            }

            // Store the new logo
            $file = $request->file('logo');
            $filePath = $file->store('TeamLogo', 'public');
            $team->logo = Storage::url($filePath); 
        }

        $team->save();

        return response()->json($team, 200);
    }




    
    public function destroy($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        // Delete the logo file if it exists
        if ($team->logo) {
            $logoPath = public_path(str_replace('/storage', 'storage', $team->logo));
            if (file_exists($logoPath)) {
                unlink($logoPath); // Delete the logo file
            }
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully'], 200);
    }

}
