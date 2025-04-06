<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Paciente;

class Medico extends Model
{
    use HasFactory;

    // Nome da tabela associada ao modelo
    protected $table = 'medicos';

    // Campos que podem ser atribuídos em massa
    protected $fillable = ['user_id', 'especialidade','crm', 'email'];

    // Relacionamento com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o modelo Paciente
    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }
}