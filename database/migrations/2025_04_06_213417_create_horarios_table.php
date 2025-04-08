<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medico_id');
            $table->dateTime('inicio');
            $table->dateTime('fim');
            $table->boolean('disponivel')->default(true);
            $table->unsignedBigInteger('agendamento_id')->nullable();
            $table->timestamps();

            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('cascade');
            // Aqui presumo que você terá uma tabela 'agendamentos' para marcar as consultas
            // $table->foreign('agendamento_id')->references('id')->on('agendamentos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};