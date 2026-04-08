<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nueva Venta</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container mt-4">

    <h4>Nueva Venta</h4>

    <!-- SELECTOR SERVICIOS -->
    <dynamic-selector
        id="serviceSelector"
        link="../../services/services/search_services.php"
        columns="Nombre,Precio"
        keys="name,price">
    </dynamic-selector>

    <!-- TABLA -->
    <table class="table mt-4" id="salesTable">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Descuento</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <h4 class="text-end">Total: $<span id="grandTotal">0.00</span></h4>

    <button class="btn btn-success" id="btnCreate">Crear Venta</button>
    <button class="btn btn-primary d-none" id="btnPrint">Imprimir</button>

</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
let items = [];
let saleCreated = false;

// 🎯 CUANDO SE SELECCIONA UN SERVICIO
document.getElementById('serviceSelector')
.addEventListener('item-selected', function(e) {

    const service = e.detail;

    const item = {
        service_id: service.id,
        name: service.name,
        description: service.description || '',
        price: parseFloat(service.price),
        discount: 0,
        total: parseFloat(service.price)
    };

    items.push(item);
    renderTable();
});

function renderTable() {
    const tbody = document.querySelector('#salesTable tbody');
    tbody.innerHTML = '';

    items.forEach((item, index) => {

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${item.name}</td>
            <td>${item.description}</td>
            <td><input type="number" class="form-control price" value="${item.price}" ${saleCreated ? 'disabled':''}></td>
            <td><input type="number" class="form-control discount" value="${item.discount}" ${saleCreated ? 'disabled':''}></td>
            <td>${item.total.toFixed(2)}</td>
            <td>
                ${saleCreated ? '' : `<button class="btn btn-danger btn-sm delete">X</button>`}
            </td>
        `;

        // EVENTOS
        if (!saleCreated) {
            tr.querySelector('.price').addEventListener('input', (e) => {
                item.price = parseFloat(e.target.value) || 0;
                updateItem(item);
            });

            tr.querySelector('.discount').addEventListener('input', (e) => {
                item.discount = parseFloat(e.target.value) || 0;
                updateItem(item);
            });

            tr.querySelector('.delete').addEventListener('click', () => {
                items.splice(index, 1);
                renderTable();
            });
        }

        tbody.appendChild(tr);
    });

    calculateTotal();
}

function updateItem(item) {
    item.total = item.price - item.discount;
    renderTable();
}

function calculateTotal() {
    let total = items.reduce((sum, i) => sum + i.total, 0);
    document.getElementById('grandTotal').innerText = total.toFixed(2);
}

// 🚀 CREAR VENTA
document.getElementById('btnCreate').addEventListener('click', async () => {

    if (items.length === 0) {
        Swal.fire('Error', 'Agrega al menos un servicio', 'error');
        return;
    }

    const res = await fetch('../../sales/services/create_sale.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ items })
    });

    const data = await res.json();

    if (data.success) {

        saleCreated = true;

        Swal.fire({
            icon: 'success',
            title: 'Venta creada',
            text: 'La venta se registró correctamente'
        });

        document.getElementById('btnCreate').classList.add('d-none');
        document.getElementById('btnPrint').classList.remove('d-none');

        renderTable();

    } else {
        Swal.fire('Error', data.message, 'error');
    }
});

// 🖨️ IMPRIMIR
document.getElementById('btnPrint').addEventListener('click', () => {
    window.print();
});

</script>

</body>
</html>