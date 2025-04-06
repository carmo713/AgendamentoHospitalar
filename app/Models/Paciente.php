<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    // Nome da tabela associada ao modelo
    protected $table = 'pacientes';

    // Campos que podem ser atribuídos em massa
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'endereco',
        'cpf',
        'data_nascimento',
        'user_id', // Adicionando user_id
    ];

    // Campos que devem ser tratados como datas
    protected $dates = [
        'data_nascimento',
    ];

    // Relacionamento com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}