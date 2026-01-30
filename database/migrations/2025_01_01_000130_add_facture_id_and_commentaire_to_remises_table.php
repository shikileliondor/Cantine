<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('remises', function (Blueprint $table) {
            $table->foreignId('facture_id')->nullable()->after('eleve_id')->constrained('factures')->nullOnDelete();
            $table->text('commentaire')->nullable()->after('valeur');
        });
    }

    public function down(): void
    {
        Schema::table('remises', function (Blueprint $table) {
            $table->dropForeign(['facture_id']);
            $table->dropColumn(['facture_id', 'commentaire']);
        });
    }
};
