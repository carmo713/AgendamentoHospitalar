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
        Schema::create('especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descricao')->nullable();
            $table->timestamps();
        });
        Schema::table('medicos', function (Blueprint $table) {
            $table->dropColumn('especialidade');
            $table->unsignedBigInteger('especialidade_id')->after('email');
            $table->foreign('especialidade_id')->references('id')->on('especialidades');
        });

    }

    public function down(): void
    {
        Schema::table('medicos', function (Blueprint $table) {
            $table->dropForeign(['especialidade_id']);
            $table->dropColumn('especialidade_id');
            $table->string('especialidade')->after('email');
        });
        
        Schema::dropIfExists('especialidades');
    }
};
