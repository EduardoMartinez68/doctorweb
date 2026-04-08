<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ver Venta</title>
    <?php include '../../../layouts/styles.php'; ?>
</head>
<body>

<?php include '../../../layouts/navbar.php'; ?>

<div class="container mt-4">

    <h4>Ver Venta</h4>

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

    
    <button class="btn btn-primary d-none" id="btnPrint">Imprimir</button>
    <button class="btn btn-danger" id="btnCancelSaleForm">Cancelar Venta</button>

</div>

<?php include '../../../layouts/scripts.php'; ?>

<script>
let items = [];
let saleCreated = false;

const params = new URLSearchParams(window.location.search);
const saleId = params.get('id');


async function loadSale() {

    const res = await fetch(`../../sales/services/get_sale.php?id=${saleId}`);
    const data = await res.json();

    if (!data.success) {
        Swal.fire('Error', data.message, 'error');
        return;
    }

    renderSale(data.sale, data.items);
}

loadSale();


function renderSale(sale, items) {

    const tbody = document.querySelector('#salesTable tbody');
    tbody.innerHTML = '';

    items.forEach(item => {

        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${item.name}</td>
            <td>${item.description}</td>
            <td><input type="number" class="form-control" value="${item.price}" disabled></td>
            <td><input type="number" class="form-control" value="${item.discount}" disabled></td>
            <td>${item.total.toFixed(2)}</td>
            <td></td>
        `;

        tbody.appendChild(tr);
    });

    document.getElementById('grandTotal').innerText = sale.total.toFixed(2);

    // 🔘 BOTONES
    const btnPrint = document.getElementById('btnPrint');
    const btnCancel = document.getElementById('btnCancelSaleForm');

    btnPrint.classList.remove('d-none');

    if (sale.status === 'completed') {
        btnCancel.classList.remove('d-none');
    }
}


document.getElementById('btnCancelSaleForm').onclick = async () => {

    const confirm = await Swal.fire({
        title: '¿Cancelar venta?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cancelar'
    });

    if (!confirm.isConfirmed) return;

    const res = await fetch(`../../sales/services/cancel_sale.php?id=${saleId}`);
    const data = await res.json();

    if (data.success) {
        Swal.fire('Cancelada', data.message, 'success');
        loadSale();
    } else {
        Swal.fire('Error', data.message, 'error');
    }
};
</script>