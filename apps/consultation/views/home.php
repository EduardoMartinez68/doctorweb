<?php
include '../../../middleware/authentication.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Ventas</title>
    <?php
    include '../../../layouts/styles.php';
    ?>
</head>

<body>
    <?php
    include '../../../layouts/navbar.php';
    ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 mb-0 fw-bold">Panel de Ventas</h2>
            <a href="medical-record.php" class="btn btn-primary btn-add px-4 shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Nueva Venta
            </a>
        </div>

        <div class="row mb-3 g-3">
            <div class="col-md-6">
                <div class="input-group search-bar shadow-sm">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0"
                        placeholder="Buscar por cliente o ID...">
                </div>
            </div>
        </div>

        <div class="table-container p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 text-uppercase small fw-bold">ID Venta</th>
                            <th class="border-0 text-uppercase small fw-bold">Cliente</th>
                            <th class="border-0 text-uppercase small fw-bold">Fecha</th>
                            <th class="border-0 text-uppercase small fw-bold">Monto</th>
                            <th class="border-0 text-uppercase small fw-bold text-center">Estado</th>
                            <th class="border-0 text-uppercase small fw-bold text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#00154</td>
                            <td class="fw-semibold">Elena Rodríguez</td>
                            <td>22 Mar, 2026</td>
                            <td>$1,250.00</td>
                            <td class="text-center">
                                <span
                                    class="badge bg-success-subtle text-success border border-success px-3">Completada</span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary border-0"><i
                                        class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#00155</td>
                            <td class="fw-semibold">Marcos Vales</td>
                            <td>21 Mar, 2026</td>
                            <td>$450.00</td>
                            <td class="text-center">
                                <span
                                    class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3">Pendiente</span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary border-0"><i
                                        class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>#00156</td>
                            <td class="fw-semibold">Sofía Pérez</td>
                            <td>20 Mar, 2026</td>
                            <td>$890.00</td>
                            <td class="text-center">
                                <span
                                    class="badge bg-danger-subtle text-danger border border-danger px-3">Cancelada</span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-secondary border-0"><i
                                        class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    include '../../../layouts/scripts.php';
    ?>
</body>

</html>