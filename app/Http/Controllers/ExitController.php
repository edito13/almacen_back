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
        ]);

        $exit = ExitRecord::create($validated);

        // Atualiza a quantidade do equipamento
        $equipment = Equipment::findOrFail($request->equipment_id);

        if ($equipment->quantity < $request->quantity) {
            return response()->json([
                'error' => true,
                'message' => 'Não há equipamento suficiente para a saída.',
            ], 400);
        }

        $equipment->quantity -= $request->quantity;
        $equipment->save();

        return response()->json($exit, 201);
    }
}
