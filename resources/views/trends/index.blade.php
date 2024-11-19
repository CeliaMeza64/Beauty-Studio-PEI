@extends('adminlte::page')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Contenedor rosa con texto blanco, sombra y texto en cursiva -->
            <h3 class="text-center bg-pink text-white p-3" style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); font-style: italic;">
                Reservas por Servicio - Mes Actual
            </h3>
            <div style="height: 300px; width: 100%; margin: auto;">
                <canvas id="reservasChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Generar colores dinámicamente
        function generarColores(count) {
            const colores = [];
            for (let i = 0; i < count; i++) {
                const r = Math.floor(Math.random() * 255);
                const g = Math.floor(Math.random() * 255);
                const b = Math.floor(Math.random() * 255);
                colores.push(`rgba(${r}, ${g}, ${b}, 0.7)`); // Color con transparencia
            }
            return colores;
        }

        // Datos del gráfico
        const labels = @json($reservasPorServicio->pluck('nombre'));
        const data = @json($reservasPorServicio->pluck('reservas_count'));
        const backgroundColors = generarColores(labels.length); // Generar colores según la cantidad de servicios

        // Configuración del gráfico
        const ctx = document.getElementById('reservasChart').getContext('2d');
        const reservasChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Reservas por Servicio',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#555',
                            font: {
                                family: 'Lobster',
                                size: 14,
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#f8bbd0',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        titleFont: {
                            family: 'Lobster',
                            size: 14
                        },
                        bodyFont: {
                            family: 'Lobster',
                            size: 12
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    </script>
@endsection
