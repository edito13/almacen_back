<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entry', 'exit']);
            $table->integer('quantity');
            $table->string('concept')->nullable();
            $table->string('responsible')->nullable();
            $table->text('details')->nullable();
            $table->date('movement_date')->nullable();
            $table->foreignId('entry_id')->nullable()->constrained('entries')->onDelete('cascade');
            $table->foreignId('exit_record_id')->nullable()->constrained('exits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
