<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;

    // Defina a tabela associada ao modelo
    protected $table = 'consultas';

    // Campos que podem ser preenchidos
    protected $fillable = [
        'user_id', // Incluído para associar o usuário à consulta
        'paciente_nome',
        'data',
        'hora',
        'status',
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
