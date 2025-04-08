<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('endereco');
            $table->string('telefone')->nullable();
            $table->timestamps();
        });

        // Adicionar unidade_id à tabela de médicos
        Schema::table('medicos', function (Blueprint $table) {
            $table->unsignedBigInteger('unidade_id')->nullable()->after('crm');
            $table->foreign('unidade_id')->references('id')->on('unidades');
        });
    }

    public function down(): void
    {
        Schema::table('medicos', function (Blueprint $table) {
            $table->dropForeign(['unidade_id']);
            $table->dropColumn('unidade_id');
        });
        
        Schema::dropIfExists('unidades');
    }
};