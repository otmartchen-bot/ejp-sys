<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::disableForeignKeyConstraints(); // <<<<<< Add this

    Schema::create('participations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('nouveau_id');
        $table->unsignedBigInteger('programme_id');
        $table->boolean('present')->default(false);
        $table->string('statut')->nullable();
        $table->text('motif_absence')->nullable();
        $table->unsignedBigInteger('marque_par');
        $table->timestamps();

        $table->foreign('nouveau_id')->references('id')->on('nouveaux')->onDelete('cascade');
        $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('cascade');
    });

    Schema::enableForeignKeyConstraints(); // <<<<<< Add this
}

    public function down()
    {
        Schema::dropIfExists('participations');
    }
};