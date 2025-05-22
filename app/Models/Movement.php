<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movement extends Model {
    use HasFactory;

    protected $fillable = [
        'entry_id',
        'exit_record_id',
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

    public function exitRecord() {
        return $this->belongsTo(ExitRecord::class, 'exit_record_id');
    }

    public function entry() {
        return $this->belongsTo(Entry::class, 'entry_id');
    }
}
