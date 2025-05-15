<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index()
    {
        return Equipment::with(['entries', 'exits'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $equipment = Equipment::create($validated);

        return response()->json($equipment, 201);
    }

    public function show($id)
    {
        $equipment = Equipment::with(['entries', 'exits'])->findOrFail($id);
        return response()->json($equipment);
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|integer|min:0',
            'type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $equipment->update($validated);

        return response()->json($equipment);
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        $equipment->delete();

        return response()->json([
            'message' => 'Equipamento deletado com sucesso.'
        ], 204);
    }
}
