<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\ExitRecord;
use Illuminate\Http\Request;

class ExitController extends Controller {
    /**
     * Display a listing of the resource.
     */

     public function index(){
        return ExitRecord::with('equipment')->get();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'quantity' => 'required|integer|min:1',
            'concept' => 'nullable|string',
            'responsible' => 'nullable|string',
            'exit_date' => 'nullable|date',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);

        // Verificar se há estoque suficiente
        if ($validated['quantity'] > $equipment->quantity) {
            return response()->json([
                'error' => true,
                'message' => 'Quantidade indisponível em estoque.'
            ]);
        }

        $exit = ExitRecord::create($validated);

        // Atualizar estoque
        $equipment->decrement('quantity', $validated['quantity']);

        // Registrar o movimento
        Movement::create([
            'equipment_id' => $exit->equipment_id,
            'type' => 'exit',
            'quantity' => $exit->quantity,
            'concept' => $exit->concept,
            'responsible' => $exit->responsible,
            'details' => null,
            'movement_date' => $exit->exit_date,
        ]);

        return response()->json($exit, 201);
    }
}
