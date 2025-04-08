<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidade extends Model
{
    use HasFactory;

    protected $table = 'unidades';
    protected $fillable = ['nome', 'endereco', 'telefone'];

    public function medicos()
    {
        return $this->hasMany(Medico::class);
    }
}