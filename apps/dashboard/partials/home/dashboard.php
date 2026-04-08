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
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: var(--med-bg);
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
</style>
<style>
    /* Contenedor de la lista */
    #nextAppointments {
        border: none;
    }

    /* Cada ítem de la cita */
    .appointment-item {
        border: none;
        border-left: 4px solid var(--med-primary);
        /* Indicador de color lateral */
        margin-bottom: 1rem;
        background: var(--med-bg);
        border-radius: 0 12px 12px 0;
        padding: 1rem 1.5rem;
        transition: all 0.2s ease;
    }

    .appointment-item:hover {
        background: #ffffff;
    }

    .appt-time {
        font-weight: 700;
        color: var(--med-primary);
        font-size: 0.9rem;
        min-width: 80px;
    }

    .appt-patient {
        font-weight: 600;
        color: var(--med-text-main);
        margin-bottom: 0;
    }

    .appt-reason {
        font-size: 0.8rem;
        color: var(--med-text-muted);
    }

    /* Badges personalizados */
    .badge-status {
        padding: 0.5rem 0.8rem;
        border-radius: 20px;
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .status-scheduled {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-confirmed {
        background: #dcfce7;
        color: #15803d;
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header mb-5">
        <h1>Dashboard Clínico</h1>
        <p class="text-muted">Bienvenido, Dr. <?php echo htmlspecialchars($_SESSION['user_name']); ?>. Aquí está el
            resumen de hoy.</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div>
                        <small class="kpi-title">Ingresos Hoy</small>
                        <h4 class="kpi-value" id="today">$0</h4>
                    </div>
                    <div class="mt-3">
                        <span class="badge-soft-success">
                            <i class="bi bi-check-circle-fill me-1"></i> Sincronizado
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div>
                        <small class="kpi-title">Meta Mensual</small>
                        <h4 class="kpi-value" id="month">$0</h4>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 6px; border-radius: 10px; background-color: #eef2ff;">
                            <div class="progress-bar"
                                style="width: 65%; background: linear-gradient(90deg, var(--med-primary), var(--med-secondary));">
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block" style="font-size: 0.7rem;">65% de la meta</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div>
                        <small class="kpi-title">Total Pacientes</small>
                        <h4 class="kpi-value" id="totalPatients">0</h4>
                    </div>
                    <div class="mt-3">
                        <p class="text-muted small mb-0" id="newPatients">
                            <i class="bi bi-person-plus-fill text-primary"></i> +0 nuevos este mes
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0"
                style="background: linear-gradient(135deg, var(--med-primary), #00357a);">
                <div class="d-flex flex-column h-100 justify-content-between text-white">
                    <div>
                        <small class="kpi-title text-white-50">Servicio Estrella</small>
                        <h4 class="fw-bold mt-2" style="font-size: 1.1rem; line-height: 1.2;" id="topService">
                            Cargando...</h4>
                    </div>
                    <div class="mt-3">
                        <div class="px-2 py-1 rounded-pill bg-white bg-opacity-10 d-inline-block small">
                            <i class="bi bi-star-fill text-warning me-1"></i> Más solicitado
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2 mb-5">
        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="kpi-title">Citas este mes</small>
                            <h4 class="kpi-value" id="appointmentsMonth">0</h4>
                        </div>
                        <div class="stat-icon-mini" style="background: #eef2ff; color: var(--med-primary);">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted small">Periodo actual</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="kpi-title">Mes anterior</small>
                            <h4 class="kpi-value text-muted" id="appointmentsLastMonth">0</h4>
                        </div>
                        <div class="stat-icon-mini" style="background: #f8fafc; color: #64748b;">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-muted small">Cerrado</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0" style="border-bottom: 3px solid #fecaca !important;">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="kpi-title">Canceladas</small>
                            <h4 class="kpi-value text-danger" id="appointmentsCancelled">0</h4>
                        </div>
                        <div class="stat-icon-mini" style="background: #fff1f2; color: #e11d48;">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 4px; background-color: #f1f5f9;">
                            <div class="progress-bar bg-danger" style="width: 15%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card p-4 h-100 shadow-sm border-0" style="border-bottom: 3px solid #a7f3d0 !important;">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="kpi-title">Completadas</small>
                            <h4 class="kpi-value text-success" id="appointmentsCompleted">0</h4>
                        </div>
                        <div class="stat-icon-mini" style="background: #ecfdf5; color: #059669;">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge-soft-success">Efectividad alta</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-3 mt-2">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <small>Citas hoy</small>
                <h4 id="appointmentsToday">0</h4>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card shadow-sm p-4 border-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="m-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Próximas Citas de Hoy</h6>
                    <a href="../../agenda/views/home.php" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        Ver agenda completa
                    </a>
                </div>
                <div id="nextAppointments">
                </div>
            </div>
        </div>
    </div>
    <br>

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
        document.getElementById('today').innerText = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.summary.today);
        document.getElementById('month').innerText = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(data.summary.month);
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
                    y: { border: { display: false }, grid: { color: '#f1f5f9' } },
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

        //----CITAS-----
        // 📅 CITAS
        document.getElementById('appointmentsMonth').innerText = data.appointments.month_total;
        document.getElementById('appointmentsLastMonth').innerText = data.appointments.last_month_total;
        document.getElementById('appointmentsCancelled').innerText = data.appointments.cancelled_month;
        document.getElementById('appointmentsCompleted').innerText = data.appointments.completed_month;
        console.log(data.appointments)
        document.getElementById('appointmentsToday').innerText = data.appointments.today;

        // ⏳ PRÓXIMAS CITAS
        // ⏳ PRÓXIMAS CITAS
        const container = document.getElementById('nextAppointments');
        container.innerHTML = '';

        if (data.next_appointments.length === 0) {
            container.innerHTML = `<p class="text-center text-muted my-4">No hay citas programadas para hoy.</p>`;
        }

        data.next_appointments.forEach(a => {
            const div = document.createElement('div');
            div.className = 'appointment-item d-flex align-items-center justify-content-between';

            // Determinar clase del badge según status
            let statusClass = 'bg-light text-dark';
            if (a.status === 'scheduled') statusClass = 'status-scheduled';
            if (a.status === 'confirmed') statusClass = 'status-confirmed';

            div.innerHTML = `
        <div class="d-flex align-items-center">
            <div class="appt-time border-end pe-3 me-3">
                <i class="bi bi-alarm me-1"></i> ${a.time.substring(0, 5)}
            </div>
            <div>
                <p class="appt-patient text-capitalize">${a.patient.toLowerCase()}</p>
                <span class="appt-reason"><i class="bi bi-clipboard2-pulse me-1"></i>${a.reason || 'Consulta general'}</span>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <span class="badge-status ${statusClass}">${a.status}</span>
            <div class="ms-3">
                <button class="btn btn-sm btn-light rounded-circle"><i class="bi bi-three-dots-vertical"></i></button>
            </div>
        </div>
    `;

            container.appendChild(div);
        });
    }

    loadDashboard();
</script>