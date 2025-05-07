<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nosotros', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->string('titulo')->nullable();
            $table->mediumText('descripcion')->nullable();
            $table->string('imagen1')->nullable();
            $table->string('titulo1')->nullable();
            $table->mediumText('descripcion1')->nullable();
            $table->string('imagen2')->nullable();
            $table->string('titulo2')->nullable();
            $table->mediumText('descripcion2')->nullable();
            $table->string('imagen3')->nullable();
            $table->string('titulo3')->nullable();
            $table->mediumText('descripcion3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nosotros');
    }
};
