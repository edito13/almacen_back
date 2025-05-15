<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitRecord extends Model {
    use HasFactory;

    protected $table = 'exits';

    protected $fillable = ['equipment_id', 'quantity'];

    public function equipment(){
        return $this->belongsTo(Equipment::class);
    }
}
