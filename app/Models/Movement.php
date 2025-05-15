<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movement extends Model {
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'type',
        'quantity',
        'concept',
        'responsible',
        'details',
        'movement_date',
    ];

    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }
}
