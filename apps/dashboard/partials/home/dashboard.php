<style>
    :root {
        --med-primary: #004AAD;
        --med-secondary: #38B6FF;
        --med-bg: #F8FAFC;
        --med-card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        --med-border: #e2e8f0;
        --med-text-main: #1e293b;
        --med-text-muted: #64748b;
    }

    /* Contenedor con Aire */
    .dashboard-container {
        padding: 2rem 1.5rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    .dashboard-header h1 {
        font-weight: 800;
        letter-spacing: -0.025em;
        color: var(--med-primary);
    }

    /* Tarjetas Tipo "Glassmorphism" Sutil */
    .card {
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        background: #ffffff;
        box-shadow: var(--med-card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    /* Indicadores (KPIs) */
    .kpi-title {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: var(--med-text-muted);
        margin-bottom: 0.5rem;
        display: block;
    }

    .kpi-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--med-primary);
        margin-bottom: 0;
    }

    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }

    .badge-soft-success {
        background-color: #ecfdf5;
        color: #059669;
        padding: 0.35rem 0.65rem;
        border-radius: 8px;
        font-size: 0.75rem;
    }

    /* Scrollbar Estilizada */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--med-bg); }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>


<div class="dashboard-container">
    <div class="dashboard-header mb-5">
        <h1>Dashboard Clínico</h1>
        <p class="text-muted">Bienvenido, Dr. <?php echo htmlspecialchars($_SESSION['user_name']); ?>. Aquí está el resumen de hoy.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card p-4">
                <small class="kpi-title">Ingresos Hoy</small>
                <h4 class="kpi-value" id="today">$0</h4>
                <div class="mt-2"><span class="badge-soft-success">Sincronizado</span></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4">
                <small class="kpi-title">Meta Mensual</small>
                <h4 class="kpi-value" id="month">$0</h4>
                <div class="progress mt-3" style="height: 6px; border-radius: 10px;">
                    <div class="progress-bar" style="width: 65%; background: linear-gradient(90deg, var(--med-primary), var(--med-secondary));"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4">
                <small class="kpi-title">Total Pacientes</small>
                <h4 class="kpi-value" id="totalPatients">0</h4>
                <p class="text-muted small mt-2 mb-0" id="newPatients">+0 nuevos este mes</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4" style="background: linear-gradient(135deg, var(--med-primary), #00357a);">
                <small class="kpi-title text-white-50">Servicio Estrella</small>
                <h4 class="text-white fw-bold mt-2" style="font-size: 1.2rem;" id="topService">Cargando...</h4>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between mb-4">
                    <h6 class="m-0 fw-bold">Tendencia de Ventas Diarias</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border small">Este Mes</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card p-4">
                <h6 class="mb-4 fw-bold">Distribución de Ingresos</h6>
                <div class="chart-container">
                    <canvas id="moneyChart"></canvas>
                </div>
                <div class="mt-4 small text-muted text-center" id="moneySummary">
                    Analizando balance de cancelaciones...
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="mb-4 fw-bold">Registro de Nuevos Pacientes</h6>
                <div class="chart-container">
                    <canvas id="patientsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4">
                <h6 class="mb-4 fw-bold">Top 5 Servicios</h6>
                <div class="chart-container">
                    <canvas id="servicesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configuración global de Chart.js para elegancia
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;

    async function loadDashboard() {
        const res = await fetch('../services/get_dashboard.php');
        const data = await res.json();

        // Llenado de datos (KPIs)
        document.getElementById('today').innerText = new Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'}).format(data.summary.today);
        document.getElementById('month').innerText = new Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'}).format(data.summary.month);
        document.getElementById('totalPatients').innerText = data.patients.total;
        document.getElementById('newPatients').innerText = `+${data.patients.new_month} nuevos este mes`;
        if (data.top_services.length > 0) document.getElementById('topService').innerText = data.top_services[0].name;

        // --- GRÁFICA DE VENTAS (LINEAL MINIMALISTA) ---
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesGradient = salesCtx.createLinearGradient(0, 0, 0, 400);
        salesGradient.addColorStop(0, 'rgba(0, 74, 173, 0.2)');
        salesGradient.addColorStop(1, 'rgba(0, 74, 173, 0)');

        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: data.chart.map(d => `${d.day}`),
                datasets: [{
                    label: 'Ventas ($)',
                    data: data.chart.map(d => d.total),
                    borderColor: '#004AAD',
                    borderWidth: 3,
                    backgroundColor: salesGradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#004AAD',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { border: {display: false}, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- GRÁFICA DE DINERO (DONA MODERNA) ---
        new Chart(document.getElementById('moneyChart'), {
            type: 'doughnut',
            data: {
                labels: ['Ingresos', 'Cancelaciones'],
                datasets: [{
                    data: [data.money.income, data.money.outcome],
                    backgroundColor: ['#004AAD', '#cbd5e1'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                cutout: '80%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // --- GRÁFICA DE PACIENTES (BARRAS REDONDEADAS) ---
        new Chart(document.getElementById('patientsChart'), {
            type: 'bar',
            data: {
                labels: data.patients_chart.map(d => `Día ${d.day}`),
                datasets: [{
                    label: 'Nuevos Pacientes',
                    data: data.patients_chart.map(d => d.total),
                    backgroundColor: '#38B6FF',
                    borderRadius: 8,
                    barThickness: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { display: false }, ticks: { stepSize: 1 } }
                }
            }
        });

        // --- SERVICIOS (DOUGHNUT) ---
        new Chart(document.getElementById('servicesChart'), {
            type: 'doughnut',
            data: {
                labels: data.top_services.map(s => s.name),
                datasets: [{
                    data: data.top_services.map(s => s.total_sold),
                    backgroundColor: ['#004AAD', '#38B6FF', '#7dd3fc', '#bae6fd', '#f1f5f9'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }

    loadDashboard();
</script>