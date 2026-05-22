<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posicion extends Model
{
    use HasFactory;

    protected $table = 'posiciones';

    protected $fillable = [
        'nombre',
    ];

    public function jugadores()
    {
        return $this->hasMany(Jugador::class);
    }
}
