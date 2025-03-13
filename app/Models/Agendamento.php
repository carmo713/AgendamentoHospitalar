<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Paciente;

class Agendamento extends Model
{
    protected $table = 'agendamentos';

    protected $fillable = [
        'medico_id',
        'paciente_id',
        'data',
        'hora',
        'descricao',
    ];

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}