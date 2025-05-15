<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\Request;

class MovementController extends Controller{
    public function index(Request $request){
        $query = Movement::with('equipment')->orderByDesc('movement_date');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('equipment_id')) {
            $query->where('equipment_id', $request->equipment_id);
        }

        if ($request->has('date')) {
            $query->whereDate('movement_date', $request->date);
        }

        return response()->json($query->get());
    }
}
