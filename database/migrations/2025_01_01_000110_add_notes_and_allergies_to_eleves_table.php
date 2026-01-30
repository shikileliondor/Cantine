<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->text('notes_internes')->nullable()->after('statut');
            $table->string('allergies_regime')->nullable()->after('notes_internes');
        });
    }

    public function down(): void
    {
        Schema::table('eleves', function (Blueprint $table) {
            $table->dropColumn(['notes_internes', 'allergies_regime']);
        });
    }
};
