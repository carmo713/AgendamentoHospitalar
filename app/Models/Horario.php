<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $fillable = ['medico_id', 'inicio', 'fim', 'disponivel', 'agendamento_id'];

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }
}