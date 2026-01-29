<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained('eleves')->cascadeOnDelete();
            $table->foreignId('facture_id')->nullable()->constrained('factures')->nullOnDelete();
            $table->date('mois');
            $table->decimal('montant', 8, 2);
            $table->date('date_paiement');
            $table->enum('mode_paiement', ['especes', 'cheque', 'virement', 'carte', 'autre']);
            $table->string('reference')->nullable();
            $table->timestamps();

            $table->index(['eleve_id', 'mois']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
