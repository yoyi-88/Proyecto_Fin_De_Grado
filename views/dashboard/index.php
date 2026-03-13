<div class="container py-5">
    <h2 class="fw-light mb-4">Resumen del Negocio</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 py-3 border-bottom border-warning border-4">
                <div class="card-body">
                    <h5 class="text-muted fw-bold small">RESERVAS PENDIENTES</h5>
                    <h1 class="display-4 fw-bold text-dark mb-0"><?= $this->resumen['pendientes'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 py-3 border-bottom border-dark border-4">
                <div class="card-body">
                    <h5 class="text-muted fw-bold small">CLIENTES REGISTRADOS</h5>
                    <h1 class="display-4 fw-bold text-dark mb-0"><?= $this->resumen['clientes'] ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center h-100 py-3 border-bottom border-success border-4">
                <div class="card-body">
                    <h5 class="text-muted fw-bold small">PLATOS EN CARTA</h5>
                    <h1 class="display-4 fw-bold text-dark mb-0"><?= $this->resumen['platos'] ?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold">Estado de las Reservas</h6>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="graficoEstados" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold">Ingresos Previstos este Año (€)</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficoIngresos" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Convertimos los datos de PHP a variables de JavaScript
    const datosEstado = <?= json_encode($this->statsEstado) ?>;
    const datosIngresos = <?= json_encode($this->statsIngresos) ?>;

    // --- GRÁFICO DE ESTADOS (DONUT) ---
    const ctxEstados = document.getElementById('graficoEstados').getContext('2d');
    
    // Mapeamos los datos para extraer etiquetas (labels) y valores (data)
    const labelsEstados = datosEstado.map(item => item.estado);
    const dataEstados = datosEstado.map(item => item.total);

    new Chart(ctxEstados, {
        type: 'doughnut',
        data: {
            labels: labelsEstados,
            datasets: [{
                data: dataEstados,
                backgroundColor: ['#ffc107', '#198754', '#dc3545', '#0dcaf0'], // Colores para Pendiente, Confirmada, Cancelada, etc.
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // --- GRÁFICO DE INGRESOS (BARRAS) ---
    const ctxIngresos = document.getElementById('graficoIngresos').getContext('2d');
    
    // Nombres de los meses fijos para mapear el número (1 = Ene, 2 = Feb...)
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
                backgroundColor: '#212529',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>