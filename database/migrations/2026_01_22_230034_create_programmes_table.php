<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->date('date_programme');
            $table->time('heure_debut');
            $table->time('heure_fin')->nullable();
            $table->string('lieu')->nullable();
            $table->foreignId('created_by')->constrained('users'); // CHANGÉ: admin_id -> created_by
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};