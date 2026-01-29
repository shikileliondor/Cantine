<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parametres_cantine', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant_mensuel', 8, 2);
            $table->unsignedTinyInteger('jour_limite_paiement');
            $table->boolean('prorata_actif')->default(false);
            $table->boolean('remises_autorisees')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parametres_cantine');
    }
};
