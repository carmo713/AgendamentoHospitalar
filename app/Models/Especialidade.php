<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidade extends Model
{
    use HasFactory;

    protected $table = 'especialidades';
    protected $fillable = ['nome', 'descricao'];

    public function medicos()
    {
        return $this->hasMany(Medico::class);
    }
}