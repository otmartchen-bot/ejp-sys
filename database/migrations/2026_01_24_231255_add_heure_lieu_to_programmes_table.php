<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            // Only add the column if it doesn't exist
            if (!Schema::hasColumn('programmes', 'heure_debut')) {
                $table->time('heure_debut')->nullable()->after('date_programme');
            }
            if (!Schema::hasColumn('programmes', 'heure_fin')) {
                $table->time('heure_fin')->nullable()->after('heure_debut');
            }
            if (!Schema::hasColumn('programmes', 'lieu')) {
                $table->string('lieu')->nullable()->after('heure_fin');
            }
        });

        // Copy existing data if needed
        if (Schema::hasColumn('programmes', 'date_programme') && Schema::hasColumn('programmes', 'heure_debut')) {
            DB::table('programmes')->whereNotNull('date_programme')->update([
                'heure_debut' => DB::raw('TIME(date_programme)')
            ]);

            DB::table('programmes')->whereNull('heure_debut')->update([
                'heure_debut' => '08:00:00'
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            if (Schema::hasColumn('programmes', 'heure_debut')) {
                $table->dropColumn('heure_debut');
            }
            if (Schema::hasColumn('programmes', 'heure_fin')) {
                $table->dropColumn('heure_fin');
            }
            if (Schema::hasColumn('programmes', 'lieu')) {
                $table->dropColumn('lieu');
            }
        });
    }
};
