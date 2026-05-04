<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galerias';

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
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
        if (!$this->imagen) {
            return null;
        }

        $normalized = str_replace('\\', '/', trim($this->imagen));

        if ($normalized === '') {
            return null;
        }

        if (Str::startsWith($normalized, ['http://', 'https://', 'storage/', '/storage/'])) {
            return ltrim($normalized, '/');
        }

        if (Str::startsWith($normalized, 'galeria/')) {
            return $normalized;
        }

        return 'galeria/' . ltrim($normalized, '/');
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('img/basket.jpeg');
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }
}
