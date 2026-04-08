<div class="container mt-4">

    <h3 class="mb-4">Dashboard</h3>

    <div class="row g-3">

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <small>Hoy</small>
                <h4 id="today">$0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <small>Semana</small>
                <h4 id="week">$0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <small>Mes</small>
                <h4 id="month">$0</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <small>Mes anterior</small>
                <h4 id="lastMonth">$0</h4>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h6>Ingresos vs Cancelaciones</h6>
                <canvas id="moneyChart"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h6>Ventas del mes</h6>
                <canvas id="salesChart"></canvas>
            </div>
        </div>

    </div>

</div>