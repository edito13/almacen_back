<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Movement;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EntryController extends Controller{
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
            'supplier'     => 'nullable|string|max:255',
            'details'      => 'nullable|string',
            'concept'      => 'required|string|max:255',
            'entry_date'   => 'required|date',
            'responsible'  => 'required|string|max:255',
            'quantity'     => 'required|integer|min:1',
        ]);

        $entry = Entry::create($validated);

        // Atualizar quantidade no estoque
        $equipment = Equipment::findOrFail($validated['equipment_id']);
        $equipment->increment('quantity', $validated['quantity']);

        // Registrar o movimento
        Movement::create([
            'entry_id'      => $entry->id,
            'equipment_id'  => $entry->equipment_id,
            'type'          => 'entry',
            'quantity'      => $entry->quantity,
            'concept'       => $entry->concept,
            'responsible'   => $entry->responsible,
            'details'       => $entry->details,
            'movement_date' => $entry->entry_date,
        ]);

        return response()->json($entry, 201);
    }

    public function show(string $id){
        $entry = Entry::findOrFail($id);
        return response()->json($entry);
    }

    public function update(Request $request, string $id){
        $entry = Entry::findOrFail($id);

        $validated = $request->validate([
            'supplier'     => 'nullable|string|max:255',
            'details'      => 'nullable|string',
            'concept'      => 'required|string|max:255',
            'entry_date'   => 'required|date',
            'responsible'  => 'required|string|max:255',
            'quantity'     => 'required|integer|min:1',
        ]);


        $oldQuantity = $entry->quantity;

        // Atualizar o estoque com base na diferença de quantidade
        $equipment = Equipment::findOrFail($entry->equipment_id);
        $difference = $validated['quantity'] - $oldQuantity;
        $equipment->increment('quantity', $difference);

        // Atualizar a entrada
        $entry->update($validated);

        // Atualizar o movimento correspondente

        $entry->movement()->update([
            'quantity'      => $entry->quantity,
            'concept'       => $entry->concept,
            'responsible'   => $entry->responsible,
            'details'       => $entry->details,
            'movement_date' => $entry->entry_date,
        ]);

        return response()->json($entry);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        $entry = Entry::findOrFail($id);
        $equipment = Equipment::findOrFail($entry->equipment_id);

        // Atualizar quantidade no estoque
        $equipment->decrement('quantity', $entry->quantity);

        // Deletar o movimento correspondente
        $entry->movement()->delete();

        // Deletar a entrada
        $entry->delete();

        return response()->json([
            'message' => 'Entrada deletada com sucesso.'
        ], 200);
    }

}
