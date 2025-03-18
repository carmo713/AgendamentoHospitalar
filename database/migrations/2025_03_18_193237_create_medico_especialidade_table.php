<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medico_especialidade', function (Blueprint $table) {
            $table->unsignedBigInteger('medico_id');
            $table->unsignedBigInteger('especialidade_id');
            $table->primary(['medico_id', 'especialidade_id']);
            
            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('cascade');
            $table->foreign('especialidade_id')->references('id')->on('especialidades')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medico_especialidade');
    }
};