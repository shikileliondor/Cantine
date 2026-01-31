<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annees_scolaires', function (Blueprint $table) {
            $table->decimal('coefficient_defaut', 6, 2)->nullable()->after('mois_personnalises');
            $table->json('trimestres')->nullable()->after('coefficient_defaut');
        });
    }

    public function down(): void
    {
        Schema::table('annees_scolaires', function (Blueprint $table) {
            $table->dropColumn(['coefficient_defaut', 'trimestres']);
        });
    }
};
