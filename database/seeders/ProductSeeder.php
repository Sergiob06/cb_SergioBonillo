<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * @var array<int, array{name: string, description: string, price: float, image: string}>
     */
    private array $products = [
        [
            'name' => 'Camiseta oficial Bellreguard CB',
            'description' => 'Camiseta roja oficial del club con tejido transpirable para entrenar o animar desde la grada.',
            'price' => 24.95,
            'image' => 'camiseta.jpg',
        ],
        [
            'name' => 'Sudadera Bellreguard Basket',
            'description' => 'Sudadera negra con detalles rojos y escudo del club. Comoda para invierno y desplazamientos.',
            'price' => 39.95,
            'image' => 'sudadera.jpeg',
        ],
        [
            'name' => 'Pantalon corto entrenamiento',
            'description' => 'Pantalon tecnico ligero para sesiones de entrenamiento y calentamientos.',
            'price' => 18.50,
            'image' => 'pantalon_corto.jpeg',
        ],
        [
            'name' => 'Chandal del club',
            'description' => 'Chandal completo del Bellreguard CB para jugadores, cuerpo tecnico y aficionados.',
            'price' => 59.90,
            'image' => 'chandal.jpeg',
        ],
        [
            'name' => 'Balon de baloncesto',
            'description' => 'Balon de entrenamiento con buen agarre para pista interior y exterior.',
            'price' => 29.95,
            'image' => 'balon.jpeg',
        ],
        [
            'name' => 'Mochila deportiva',
            'description' => 'Mochila amplia para zapatillas, ropa de entreno, botella y material personal.',
            'price' => 32.00,
            'image' => 'mochila.jpeg',
        ],
        [
            'name' => 'Bufanda del club',
            'description' => 'Bufanda roja y blanca para animar al Bellreguard CB en cada partido.',
            'price' => 14.95,
            'image' => 'bufanda.jpeg',
        ],
        [
            'name' => 'Botella deportiva',
            'description' => 'Botella reutilizable con diseño del club para entrenamientos y partidos.',
            'price' => 9.95,
            'image' => 'botella.jpeg',
        ],
        [
            'name' => 'Calcetines deportivos',
            'description' => 'Calcetines comodos de entrenamiento con detalle rojo Bellreguard.',
            'price' => 7.50,
            'image' => 'calcetines.jpeg',
        ],
        [
            'name' => 'Pack entrenamiento',
            'description' => 'Pack completo con camiseta, botella y balon para empezar la temporada equipado.',
            'price' => 49.90,
            'image' => 'pack_entrenamiento.jpeg',
        ],
    ];

    public function run(): void
    {
        foreach ($this->products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                [
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'image' => $this->imagePath($product['image'], $product['name']),
                ]
            );
        }
    }

    private function imagePath(string $preferredImage, string $productName): string
    {
        $directory = public_path('productos');
        $preferredPath = $directory . DIRECTORY_SEPARATOR . $preferredImage;

        if (is_file($preferredPath)) {
            return 'productos/' . $preferredImage;
        }

        return 'img/basket.jpeg';
    }
}
