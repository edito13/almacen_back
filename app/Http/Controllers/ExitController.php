<?php

namespace App\Http\Controllers;

use App\Models\Movement;
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
            'exit_record_id' => $exit->id,
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

    public function show(string $id){
        $exit = ExitRecord::findOrFail($id);
        return response()->json($exit);
    }

    public function update(Request $request, string $id){
        $exit = ExitRecord::findOrFail($id);

        $validated = $request->validate([
            'quantity'     => 'required|integer|min:1',
            'concept'      => 'nullable|string',
            'responsible'  => 'nullable|string',
            'exit_date'    => 'nullable|date',
        ]);

        $equipment = Equipment::findOrFail($exit->equipment_id);

        // Devolver a quantidade antiga ao estoque
        $equipment->increment('quantity', $exit->quantity);

        // Verificar se o novo valor pode ser retirado
        if ($validated['quantity'] > $equipment->quantity) {
            return response()->json([
                'error' => true,
                'message' => 'Nova quantidade excede o estoque disponível.'
            ], 422);
        }

        // Atualizar a saída
        $exit->update($validated);

        // Atualizar o estoque com a nova quantidade
        $equipment->decrement('quantity', $validated['quantity']);

        // Atualizar movimento
         $exit->movement()->update([
            'quantity'      => $exit->quantity,
            'concept'       => $exit->concept,
            'responsible'   => $exit->responsible,
            'movement_date' => $exit->exit_date,
        ]);

        return response()->json($exit);
    }


    public function destroy(string $id){
        $exit = ExitRecord::findOrFail($id);

        // Devolver quantidade ao estoque
        $equipment = Equipment::findOrFail($exit->equipment_id);
        $equipment->increment('quantity', $exit->quantity);

        // Deletar movimento
        $exit->movement()->delete();

        // Deletar saída
        $exit->delete();

        return response()->json(['message' => 'Saída removida com sucesso.'], 200);
    }

}
