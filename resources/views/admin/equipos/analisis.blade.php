@extends('layouts.admin')

@section('contenido_admin')
<header class="header-admin">
    <div>
        <h2>Análisis de {{ $equipo->nombre }}</h2>
        <p class="admin-analysis-subtitle">Datos calculados automáticamente desde los partidos jugados</p>
    </div>
    <div class="admin-analysis-actions">
        <a href="{{ route('equipos.show', $equipo) }}" class="btn-nuevo admin-analysis-action admin-analysis-action--team"><i class="fas fa-eye"></i> Ver equipo</a>
        <a href="{{ route('equipos.index') }}" class="btn-nuevo admin-analysis-action admin-analysis-action--back"><i class="fas fa-arrow-left"></i> Volver</a>
    </div>
</header>

<div class="pizarra-admin admin-analysis-panel">
    @if($analisisEquipo['partidos_jugados'] === 0)
        <div class="admin-analysis-empty">
            <i class="fas fa-chart-line"></i>
            <h3>Sin datos suficientes</h3>
            <p>Todavía no hay partidos jugados con resultado para analizar este equipo.</p>
        </div>
    @else
        @php
            $winRate = $analisisEquipo['partidos_jugados'] > 0
                ? round(($analisisEquipo['victorias'] / $analisisEquipo['partidos_jugados']) * 100)
                : 0;
            $hasTrendData = $analisisEquipo['partidos_jugados'] >= 2;
        @endphp

        <div class="admin-analysis-hero">
            <div>
                <span class="admin-analysis-eyebrow">Rendimiento del equipo</span>
                <h3>{{ $equipo->nombre }}</h3>
                <p>{{ $equipo->category?->name ?? $equipo->categoria }} · {{ $analisisEquipo['partidos_jugados'] }} partidos jugados</p>
            </div>
            <div class="admin-analysis-record">
                <strong>{{ $analisisEquipo['victorias'] }}-{{ $analisisEquipo['derrotas'] }}</strong>
                <span>{{ $winRate }}% victorias</span>
            </div>
        </div>

        <div class="admin-analysis-kpis">
            <div class="admin-kpi-card admin-kpi-card--red">
                <span>Media anotados</span>
                <strong>{{ $analisisEquipo['media_puntos_anotados'] }}</strong>
                <small>Puntos por partido</small>
            </div>
            <div class="admin-kpi-card admin-kpi-card--dark">
                <span>Media recibidos</span>
                <strong>{{ $analisisEquipo['media_puntos_recibidos'] }}</strong>
                <small>Defensa promedio</small>
            </div>
            <div class="admin-kpi-card admin-kpi-card--green">
                <span>Diferencia media</span>
                <strong>{{ $analisisEquipo['diferencia_media'] > 0 ? '+' : '' }}{{ $analisisEquipo['diferencia_media'] }}</strong>
                <small>Margen por partido</small>
            </div>
            <div class="admin-kpi-card admin-kpi-card--gold">
                <span>Porcentaje victorias</span>
                <strong>{{ $winRate }}%</strong>
                <small>{{ $analisisEquipo['victorias'] }} de {{ $analisisEquipo['partidos_jugados'] }}</small>
            </div>
        </div>

        <div class="admin-analysis-grid admin-analysis-grid--charts">
            <div class="admin-chart-card admin-chart-card--wide">
                <div class="admin-chart-header">
                    <div>
                        <h3>Evolución de puntos</h3>
                        <p>Puntos anotados y recibidos en cada partido</p>
                    </div>
                    <span class="admin-chart-badge">{{ $analisisEquipo['partidos_jugados'] }} partidos</span>
                </div>
                <div class="admin-chart-summary">
                    <span><strong>{{ $analisisEquipo['media_puntos_anotados'] }}</strong> media anotados</span>
                    <span><strong>{{ $analisisEquipo['media_puntos_recibidos'] }}</strong> media recibidos</span>
                    <span><strong>{{ $analisisEquipo['diferencia_media'] > 0 ? '+' : '' }}{{ $analisisEquipo['diferencia_media'] }}</strong> diferencia media</span>
                </div>
                @if($hasTrendData)
                    <div class="admin-chart-canvas">
                        <canvas id="pointsChart"></canvas>
                    </div>
                @else
                    <div class="admin-chart-empty">Hace falta al menos un segundo partido para ver una evolución clara.</div>
                @endif
            </div>

            <div class="admin-chart-card">
                <div class="admin-chart-header">
                    <div>
                        <h3>Balance competitivo</h3>
                        <p>Relación entre victorias y derrotas</p>
                    </div>
                </div>
                <div class="admin-chart-summary admin-chart-summary--compact">
                    <span><strong>{{ $winRate }}%</strong> victorias</span>
                    <span><strong>{{ $analisisEquipo['victorias'] }}</strong> V</span>
                    <span><strong>{{ $analisisEquipo['derrotas'] }}</strong> D</span>
                </div>
                <div class="admin-chart-canvas admin-chart-canvas--small">
                    <canvas id="balanceChart"></canvas>
                </div>
            </div>

            <div class="admin-chart-card">
                <div class="admin-chart-header">
                    <div>
                        <h3>Diferencia por partido</h3>
                        <p>Margen positivo o negativo en el marcador</p>
                    </div>
                </div>
                <div class="admin-chart-summary admin-chart-summary--compact">
                    <span><strong>{{ $analisisEquipo['diferencia_media'] > 0 ? '+' : '' }}{{ $analisisEquipo['diferencia_media'] }}</strong> margen medio</span>
                </div>
                @if($hasTrendData)
                    <div class="admin-chart-canvas admin-chart-canvas--small">
                        <canvas id="diffChart"></canvas>
                    </div>
                @else
                    <div class="admin-chart-empty">Aún no hay suficientes partidos para comparar diferencias.</div>
                @endif
            </div>
        </div>

        <div class="admin-analysis-highlights">
            <div class="admin-highlight-card">
                <div class="admin-highlight-icon admin-highlight-icon--red"><i class="fas fa-arrow-up"></i></div>
                <div>
                    <h4>Mejor partido ofensivo</h4>
                @php($mejor = $analisisEquipo['mejor_partido_ofensivo'])
                    <p>{{ $mejor?->equipoLocal?->nombre ?? $mejor?->equipo_local }} vs {{ $mejor?->equipoVisitante?->nombre ?? $mejor?->equipo_visitante }}</p>
                    <strong>{{ $mejor?->puntos_anotados }} puntos · {{ $mejor?->fecha_partido?->format('d/m/Y') }}</strong>
                </div>
            </div>
            <div class="admin-highlight-card">
                <div class="admin-highlight-icon admin-highlight-icon--dark"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <h4>Peor partido defensivo</h4>
                @php($peor = $analisisEquipo['peor_partido_defensivo'])
                    <p>{{ $peor?->equipoLocal?->nombre ?? $peor?->equipo_local }} vs {{ $peor?->equipoVisitante?->nombre ?? $peor?->equipo_visitante }}</p>
                    <strong>{{ $peor?->puntos_recibidos }} puntos recibidos · {{ $peor?->fecha_partido?->format('d/m/Y') }}</strong>
                </div>
            </div>
        </div>
    @endif
</div>

@if($analisisEquipo['partidos_jugados'] > 0)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);
        const hasTrendData = @json($hasTrendData);
        const red = '#cf1515';
        const redDark = '#a91111';
        const redSoft = 'rgba(207, 21, 21, 0.12)';
        const dark = '#1f2937';
        const slate = '#475569';
        const green = '#16a34a';

        Chart.defaults.font.family = "'Inter', 'Segoe UI', system-ui, sans-serif";
        Chart.defaults.color = slate;

        const commonPlugins = {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle',
                    boxWidth: 8,
                    boxHeight: 8,
                    padding: 18,
                    color: '#334155',
                    font: { size: 12, weight: '700' },
                },
            },
            tooltip: {
                enabled: true,
                backgroundColor: '#111827',
                titleColor: '#ffffff',
                bodyColor: '#e5e7eb',
                borderColor: 'rgba(255, 255, 255, 0.16)',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 10,
                displayColors: true,
                titleFont: { weight: '800' },
                bodyFont: { weight: '600' },
                callbacks: {
                    label: (context) => {
                        const value = context.parsed.y ?? context.parsed;
                        return `${context.dataset.label}: ${value} pts`;
                    },
                },
            },
        };

        const cleanScales = {
            x: {
                grid: { display: false },
                border: { display: false },
                ticks: { maxRotation: 0, autoSkip: true, color: '#64748b', font: { size: 11, weight: '600' } },
            },
            y: {
                beginAtZero: true,
                border: { display: false },
                grid: { color: 'rgba(148, 163, 184, 0.18)' },
                ticks: { precision: 0, color: '#64748b', font: { size: 11, weight: '600' } },
            },
        };

        if (hasTrendData) {
            new Chart(document.getElementById('pointsChart'), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Puntos anotados',
                            data: chartData.puntosAnotados,
                            borderColor: red,
                            backgroundColor: redSoft,
                            pointBackgroundColor: red,
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 6,
                            borderWidth: 3,
                            tension: .36,
                            fill: true,
                        },
                        {
                            label: 'Puntos recibidos',
                            data: chartData.puntosRecibidos,
                            borderColor: dark,
                            backgroundColor: 'rgba(31, 41, 55, .08)',
                            pointBackgroundColor: dark,
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 6,
                            borderWidth: 3,
                            tension: .36,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: commonPlugins,
                    scales: cleanScales,
                }
            });
        }

        new Chart(document.getElementById('balanceChart'), {
            type: 'doughnut',
            data: {
                labels: ['Victorias', 'Derrotas'],
                datasets: [{
                    data: [chartData.victorias, chartData.derrotas],
                    backgroundColor: [green, red],
                    borderColor: '#ffffff',
                    borderWidth: 4,
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    ...commonPlugins,
                    tooltip: {
                        ...commonPlugins.tooltip,
                        callbacks: {
                            label: (context) => `${context.label}: ${context.parsed} partidos`,
                        },
                    },
                },
            }
        });

        if (hasTrendData) {
            new Chart(document.getElementById('diffChart'), {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Diferencia',
                        data: chartData.diferencias,
                        backgroundColor: chartData.diferencias.map(value => value >= 0 ? green : redDark),
                        hoverBackgroundColor: chartData.diferencias.map(value => value >= 0 ? '#15803d' : red),
                        borderRadius: 10,
                        borderSkipped: false,
                        maxBarThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: commonPlugins,
                    scales: {
                        x: cleanScales.x,
                        y: {
                            border: { display: false },
                            grid: { color: 'rgba(148, 163, 184, 0.18)' },
                            ticks: { precision: 0, color: '#64748b', font: { size: 11, weight: '600' } },
                        },
                    },
                }
            });
        }
    </script>
@endif
@endsection
