@extends('layouts.admin')

@section('contenido_admin')
@php($esAdmin = auth()->user()?->rol === 'admin')
<section class="admin-dashboard">
<header class="header-admin admin-dashboard-header">
    <div class="admin-dashboard-heading">
        <h2>Bienvenido al Panel</h2>
        <p class="admin-dashboard-resumen">Resumen del contenido que ya está persistido en la base de datos</p>
    </div>
</header>

<div class="pizarra-admin admin-dashboard-panel">
    <div class="admin-dashboard-grid" aria-label="Resumen de metricas del panel admin">
        <article class="tarjeta-vacia admin-dashboard-card">
            <div class="admin-dashboard-icono">
                <i class="fas fa-tshirt" aria-hidden="true"></i>
            </div>
            <div class="admin-dashboard-card-body">
                <h3>{{ $resumenAdmin['equipos'] }}</h3>
                <p>Equipos</p>
            </div>
        </article>
        <article class="tarjeta-vacia admin-dashboard-card">
            <div class="admin-dashboard-icono">
                <i class="fas fa-users" aria-hidden="true"></i>
            </div>
            <div class="admin-dashboard-card-body">
                <h3>{{ $resumenAdmin['jugadores'] }}</h3>
                <p>Jugadores</p>
            </div>
        </article>
        <article class="tarjeta-vacia admin-dashboard-card">
            <div class="admin-dashboard-icono">
                <i class="fas fa-calendar-alt" aria-hidden="true"></i>
            </div>
            <div class="admin-dashboard-card-body">
                <h3>{{ $resumenAdmin['partidos'] }}</h3>
                <p>Partidos</p>
            </div>
        </article>
        <article class="tarjeta-vacia admin-dashboard-card">
            <div class="admin-dashboard-icono">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
            </div>
            <div class="admin-dashboard-card-body">
                <h3>{{ $resumenAdmin['estadisticas'] }}</h3>
                <p>Estadísticas</p>
            </div>
        </article>
        @if($esAdmin)
            <article class="tarjeta-vacia admin-dashboard-card">
                <div class="admin-dashboard-icono">
                    <i class="fas fa-camera" aria-hidden="true"></i>
                </div>
                <div class="admin-dashboard-card-body">
                    <h3>{{ $resumenAdmin['galerias'] }}</h3>
                    <p>Galería</p>
                </div>
            </article>
            <article class="tarjeta-vacia admin-dashboard-card">
                <div class="admin-dashboard-icono">
                    <i class="fas fa-bag-shopping" aria-hidden="true"></i>
                </div>
                <div class="admin-dashboard-card-body">
                    <h3>{{ $resumenAdmin['productos'] }}</h3>
                    <p>Productos</p>
                </div>
            </article>
        @endif
    </div>
</div>
</section>
@endsection
