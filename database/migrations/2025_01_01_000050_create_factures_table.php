<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->date('mois');
            $table->decimal('montant_mensuel', 8, 2);
            $table->decimal('montant_remise', 8, 2)->default(0);
            $table->decimal('montant_total', 8, 2);
            $table->date('date_limite')->nullable();
            $table->enum('statut', ['a_jour', 'partiel', 'retard', 'non_concerne'])->default('partiel');
            $table->timestamps();

            $table->unique(['eleve_id', 'mois']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
