<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return Entry::with('equipment')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $entry = Entry::create($validated);

        // Atualiza a quantidade do equipamento
        $equipment = Equipment::findOrFail($request->equipment_id);
        $equipment->quantity += $request->quantity;
        $equipment->save();

        return response()->json($entry, 201);
    }
}
