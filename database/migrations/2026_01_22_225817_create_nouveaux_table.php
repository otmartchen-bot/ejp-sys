<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nouveaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aide_id')->constrained('users')->onDelete('cascade');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('profession');
            $table->string('fij');
            $table->date('date_enregistrement');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nouveaux');
    }
};