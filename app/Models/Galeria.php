<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Support\ImagePath;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galerias';

    protected $fillable = [
        'titulo',
        'descripcion',
        'imagen',
        'fecha_imagen',
    ];

    protected $casts = [
        'fecha_imagen' => 'date',
    ];

    protected $appends = [
        'image',
        'image_url',
    ];

    public function getImageAttribute(): ?string
    {
        return ImagePath::normalize($this->imagen, 'galeria');
    }

    public function getImageUrlAttribute(): string
    {
        return ImagePath::url($this->imagen, 'galeria');
    }
}
