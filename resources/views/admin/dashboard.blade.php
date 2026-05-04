@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Bienvenido al Panel</h2>
        <p style="color: #777;">Resumen del contenido que ya está persistido en la base de datos</p>
    </div>
</header>

<div class="pizarra-admin">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px;">
        <div class="tarjeta-vacia">
            <i class="fas fa-tshirt"></i>
            <h3>{{ $resumenAdmin['equipos'] }}</h3>
            <p>Equipos</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-users"></i>
            <h3>{{ $resumenAdmin['jugadores'] }}</h3>
            <p>Jugadores</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-calendar-alt"></i>
            <h3>{{ $resumenAdmin['partidos'] }}</h3>
            <p>Partidos</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-chart-line"></i>
            <h3>{{ $resumenAdmin['estadisticas'] }}</h3>
            <p>Estadísticas</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-trophy"></i>
            <h3>{{ $resumenAdmin['clasificaciones'] }}</h3>
            <p>Clasificación</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-camera"></i>
            <h3>{{ $resumenAdmin['galerias'] }}</h3>
            <p>Galería</p>
        </div>
        <div class="tarjeta-vacia">
            <i class="fas fa-bag-shopping"></i>
            <h3>{{ $resumenAdmin['productos'] }}</h3>
            <p>Productos</p>
        </div>
    </div>
</div>
@endsection
