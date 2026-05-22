<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Support\ImagePath;

class Product extends Model
{
    use HasFactory;

    private const IMAGE_DIRECTORIES = ['productos', 'products'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImagePathAttribute(): ?string
    {
        return ImagePath::normalizeFromDirectories($this->image, self::IMAGE_DIRECTORIES);
    }

    public function getImageUrlAttribute(): ?string
    {
        return ImagePath::urlFromDirectories($this->image, self::IMAGE_DIRECTORIES);
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(ProductoSolicitud::class);
    }
}
