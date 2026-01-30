<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annees_scolaires', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('annee_debut');
            $table->unsignedSmallInteger('annee_fin');
            $table->json('mois_personnalises')->nullable();
            $table->boolean('est_active')->default(false);
            $table->boolean('est_cloturee')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annees_scolaires');
    }
};
