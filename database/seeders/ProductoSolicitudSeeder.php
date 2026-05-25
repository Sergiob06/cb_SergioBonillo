<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductoSolicitud;
use Illuminate\Database\Seeder;

class ProductoSolicitudSeeder extends Seeder
{
    /**
     * @var array<int, array{product:string,nombre:string,email:string,telefono:?string,mensaje:?string,estado:string,created_at:string}>
     */
    private array $requests = [
        [
            'product' => 'Mochila deportiva',
            'nombre' => 'Sergio',
            'email' => 'correo@gmail.com',
            'telefono' => null,
            'mensaje' => null,
            'estado' => ProductoSolicitud::ESTADO_PENDIENTE,
            'created_at' => '2026-05-21 21:40:02',
        ],
        [
            'product' => 'Pantalon corto entrenamiento',
            'nombre' => 'sergio',
            'email' => 'prueba@gmail.com',
            'telefono' => null,
            'mensaje' => null,
            'estado' => ProductoSolicitud::ESTADO_PENDIENTE,
            'created_at' => '2026-05-21 21:50:48',
        ],
        [
            'product' => 'Chandal del club',
            'nombre' => 'Sergio',
            'email' => 'prueba@gmail.com',
            'telefono' => null,
            'mensaje' => null,
            'estado' => ProductoSolicitud::ESTADO_COMPLETADA,
            'created_at' => '2026-05-22 11:08:19',
        ],
    ];

    public function run(): void
    {
        foreach ($this->requests as $request) {
            $product = Product::where('name', $request['product'])->first();

            if (! $product) {
                continue;
            }

            $solicitud = ProductoSolicitud::updateOrCreate([
                'product_id' => $product->id,
                'email' => $request['email'],
                'nombre' => $request['nombre'],
                'estado' => $request['estado'],
            ], [
                'telefono' => $request['telefono'],
                'mensaje' => $request['mensaje'],
            ]);

            $solicitud->forceFill([
                'created_at' => $request['created_at'],
                'updated_at' => $request['created_at'],
            ])->save();
        }
    }
}
