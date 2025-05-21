<?php

namespace App\Models;

use App\Models\Entry;
use App\Models\Category;
use App\Models\ExitRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipment extends Model {
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'min_quantity', 'category_id', "type"];

    public function entries(){
        return $this->hasMany(Entry::class);
    }

    public function exits(){
        return $this->hasMany(ExitRecord::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
