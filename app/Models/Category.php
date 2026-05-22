<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }
}
