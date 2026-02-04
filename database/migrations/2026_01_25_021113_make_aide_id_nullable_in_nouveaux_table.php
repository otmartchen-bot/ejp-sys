<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nouveaux', function (Blueprint $table) {
            // Rendre aide_id nullable
            $table->foreignId('aide_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('nouveaux', function (Blueprint $table) {
            // Revenir à non nullable (attention aux données existantes)
            $table->foreignId('aide_id')->nullable(false)->change();
        });
    }
};