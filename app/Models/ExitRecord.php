<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExitRecord extends Model {
    use HasFactory;

    protected $table = 'exits';

    protected $fillable = [
        'equipment_id',
        'quantity',
        'concept',
        'responsible',
        'exit_date',
    ];

    public function equipment() {
        return $this->belongsTo(Equipment::class);
    }

    public function movement() {
        return $this->hasOne(Movement::class, 'exit_record_id');
    }
}
