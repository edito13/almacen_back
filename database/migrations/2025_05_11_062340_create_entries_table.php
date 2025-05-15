<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->string('supplier')->nullable();
            $table->text('details')->nullable();
            $table->integer('min_quantity')->nullable();
            $table->string('concept')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('responsible')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('entries');
    }
};
