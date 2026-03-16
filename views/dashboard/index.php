<div class="container dashboard-page py-5">
    
    <div class="dashboard-header mb-4">
        <h2 class="dashboard-title"><i class="bi bi-graph-up-arrow text-primary me-2"></i> Resumen del Negocio</h2>
        <p class="text-muted">Vista general de la actividad de De Mi Casa a la Tuya.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="widget-card shadow-sm widget-warning">
                <div class="widget-icon bg-warning text-dark"><i class="bi bi-calendar-check"></i></div>
                <div class="widget-content">
                    <h5 class="widget-label">RESERVAS PENDIENTES</h5>
                    <h1 class="widget-value"><?= $this->resumen['pendientes'] ?></h1>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="widget-card shadow-sm widget-dark">
                <div class="widget-icon bg-dark text-white"><i class="bi bi-people"></i></div>
                <div class="widget-content">
                    <h5 class="widget-label">CLIENTES REGISTRADOS</h5>
                    <h1 class="widget-value"><?= $this->resumen['clientes'] ?></h1>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="widget-card shadow-sm widget-success">
                <div class="widget-icon bg-success text-white"><i class="bi bi-cup-hot"></i></div>
                <div class="widget-content">
                    <h5 class="widget-label">PLATOS EN CARTA</h5>
                    <h1 class="widget-value"><?= $this->resumen['platos'] ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="chart-card shadow-sm h-100">
                <div class="chart-card-header">
                    <h6 class="chart-title">Estado de las Reservas</h6>
                </div>
                <div class="chart-card-body d-flex justify-content-center align-items-center">
                    <div class="chart-container-donut">
                        <canvas id="graficoEstados"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="chart-card shadow-sm h-100">
                <div class="chart-card-header">
                    <h6 class="chart-title">Ingresos Previstos este Año (€)</h6>
                </div>
                <div class="chart-card-body">
                    <div class="chart-container-bar">
                        <canvas id="graficoIngresos"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    const datosEstado = <?= json_encode($this->statsEstado) ?>;
    const datosIngresos = <?= json_encode($this->statsIngresos) ?>;

    // --- GRÁFICO DE ESTADOS (DONUT) ---
    const ctxEstados = document.getElementById('graficoEstados').getContext('2d');
    const labelsEstados = datosEstado.map(item => item.estado);
    const dataEstados = datosEstado.map(item => item.total);

    // Mapeo de colores corporativos según el estado para que coincida con tu diseño
    const colorMap = {
        'Pendiente': '#ffc107',  // Warning
        'Confirmada': '#198754', // Success
        'Cancelada': '#dc3545',  // Danger
        'Finalizada': '#212529'  // Dark
    };
    
    // Asignamos colores a cada etiqueta dinámicamente
    const bgColorsEstados = labelsEstados.map(estado => colorMap[estado] || '#0dcaf0');

    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: labelsEstados,
            datasets: [{
                data: dataEstados,
                backgroundColor: bgColorsEstados,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom',
                    labels: { font: { family: "'Lato', sans-serif" } }
                } 
            },
            cutout: '70%' // Hace el agujero central más elegante
        }
    });

    // --- GRÁFICO DE INGRESOS (BARRAS) ---
    const ctxIngresos = document.getElementById('graficoIngresos').getContext('2d');
    const nombresMeses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const labelsIngresos = datosIngresos.map(item => nombresMeses[item.mes - 1]);
    const dataIngresos = datosIngresos.map(item => item.total_ingresos);

    new Chart(ctxIngresos, {
        type: 'bar',
        data: {
            labels: labelsIngresos,
            datasets: [{
                label: 'Ingresos (€)',
                data: dataIngresos,
                backgroundColor: '#D4AF37', // Tu color $primary dorado
                borderRadius: 6,            // Bordes superiores redondeados
                borderSkipped: false,
                barPercentage: 0.6          // Barras más estilizadas
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false } // Ocultamos la leyenda porque es obvio que es de ingresos
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { borderDash: [5, 5] }, // Líneas de fondo punteadas (más limpio)
                    ticks: { font: { family: "'Lato', sans-serif" } }
                },
                x: {
                    grid: { display: false }, // Ocultamos las líneas verticales
                    ticks: { font: { family: "'Lato', sans-serif" } }
                }
            }
        }
    });
});
</script>