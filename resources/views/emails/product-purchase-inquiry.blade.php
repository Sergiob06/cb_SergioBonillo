<h2>Nueva solicitud de compra</h2>

<p><strong>Producto:</strong> {{ $product->name }}</p>
<p><strong>Precio:</strong> {{ number_format((float) $product->price, 2, ',', '.') }} EUR</p>
<p><strong>Nombre:</strong> {{ $customerData['name'] }}</p>
<p><strong>Email:</strong> {{ $customerData['email'] }}</p>
<p><strong>Telefono:</strong> {{ $customerData['telefono'] ?? 'No indicado.' }}</p>
<p><strong>Mensaje:</strong> {{ $customerData['message'] ?? 'Sin mensaje adicional.' }}</p>
