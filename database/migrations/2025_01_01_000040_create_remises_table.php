<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->nullable()->constrained('eleves')->nullOnDelete();
            $table->string('libelle');
            $table->enum('type_remise', ['fixe', 'pourcentage']);
            $table->decimal('valeur', 8, 2);
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remises');
    }
};
