<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->string('type_plateforme', 100)->nullable()->after('situation');
            $table->string('categorie_probleme', 100)->nullable()->after('type_plateforme');
            $table->string('contexte_geo', 50)->nullable()->after('categorie_probleme');
            $table->json('aspects')->nullable()->after('contexte_geo');
            $table->string('niveau_risque', 20)->nullable()->after('categorie');
            $table->string('nature_probleme', 100)->nullable()->after('niveau_risque');
            $table->text('sanctions')->nullable()->after('recommandations');
            $table->string('autorite', 500)->nullable()->after('sanctions');
        });
    }

    public function down(): void
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->dropColumn([
                'type_plateforme', 'categorie_probleme', 'contexte_geo', 'aspects',
                'niveau_risque', 'nature_probleme', 'sanctions', 'autorite',
            ]);
        });
    }
};
