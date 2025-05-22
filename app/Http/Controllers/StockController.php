<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class StockController extends Controller {
    public function index() {
        // Retorna todos os equipamentos com a categoria (se quiser)
        $equipments = Equipment::with(['entries', 'exits', 'category'])->get();

        return response()->json($equipments);
    }
}
