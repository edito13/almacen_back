<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entry extends Model {
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'quantity',
        'supplier',
        'details',
        'concept',
        'entry_date',
        'responsible',
    ];

    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }
}
