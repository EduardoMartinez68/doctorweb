<table class="table align-middle">
    <thead>
        <tr>
            <th>Servicio</th>
            <th>Descripción</th>
            <th class="text-end">Cantidad</th>
            <th class="text-end">Precio Unit.</th>
            <th class="text-end">Descuento (%)</th>
            <th class="text-end">Subtotal</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="tablaLineas">
        <tr>
            <td style="width: 25%;">
                <input type="text" class="form-control form-control-sm" placeholder="..." value="Consulta Médica">
            </td>
            <td><input type="text" class="form-control form-control-sm" placeholder="..."></td>
            <td class="text-end" style="width: 10%;"><input type="number" class="form-control form-control-sm text-end"
                    value="1"></td>
            <td class="text-end" style="width: 12%;">$ 0.00</td>
            <td class="text-end" style="width: 12%;"><input type="number" class="form-control form-control-sm text-end"
                    value="0.00"></td>
            <td class="text-end fw-bold">$0.00</td>
            <td class="text-end"><i class="bi bi-trash text-muted"></i></td>
        </tr>
    </tbody>
</table>
<button class="btn btn-link text-decoration-none p-0 text-primary" id="btnAgregarLinea">+ Agregar
    línea</button>



<script>
    const tabla = document.getElementById("tablaLineas");
    const btnAgregar = document.getElementById("btnAgregarLinea");

    let lineas = [];

    // 🔹 Crear nueva línea
    function crearLinea() {
        return {
            servicio: "",
            descripcion: "",
            cantidad: 1,
            precio: 0,
            descuento: 0,
            subtotal: 0
        };
    }

    // 🔹 Renderizar tabla
    function renderTabla() {
        tabla.innerHTML = "";

        lineas.forEach((linea, index) => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
            <td>
                <input type="text" class="form-control form-control-sm servicio" value="${linea.servicio}">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm descripcion" value="${linea.descripcion}">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end cantidad" value="${linea.cantidad}">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end precio" value="${linea.precio}">
            </td>
            <td class="text-end">
                <input type="number" class="form-control form-control-sm text-end descuento" value="${linea.descuento}">
            </td>
            <td class="text-end fw-bold subtotal">$ 0.00</td>
            <td class="text-end">
                <button class="btn btn-sm text-danger eliminar">🗑</button>
            </td>
        `;

            // Eventos
            tr.querySelector(".cantidad").addEventListener("input", e => {
                lineas[index].cantidad = parseFloat(e.target.value) || 0;
                actualizarLinea(index);
            });

            tr.querySelector(".precio").addEventListener("input", e => {
                lineas[index].precio = parseFloat(e.target.value) || 0;
                actualizarLinea(index);
            });

            tr.querySelector(".descuento").addEventListener("input", e => {
                lineas[index].descuento = parseFloat(e.target.value) || 0;
                actualizarLinea(index);
            });

            tr.querySelector(".servicio").addEventListener("input", e => {
                lineas[index].servicio = e.target.value;
            });

            tr.querySelector(".descripcion").addEventListener("input", e => {
                lineas[index].descripcion = e.target.value;
            });

            tr.querySelector(".eliminar").addEventListener("click", () => {
                lineas.splice(index, 1);
                renderTabla();
                calcularTotales();
            });

            tabla.appendChild(tr);

            actualizarLinea(index, tr);
        });
    }

    // 🔹 Actualizar una línea
    function actualizarLinea(index, tr = null) {
        const linea = lineas[index];

        const subtotalSinDescuento = linea.cantidad * linea.precio;
        const descuento = subtotalSinDescuento * (linea.descuento / 100);
        linea.subtotal = subtotalSinDescuento - descuento;

        // actualizar UI
        if (!tr) tr = tabla.children[index];
        tr.querySelector(".subtotal").innerText = "$ " + linea.subtotal.toFixed(2);

        calcularTotales();
    }

    // 🔹 Calcular totales globales
    function calcularTotales() {
        let subtotal = 0;
        let descuentos = 0;

        lineas.forEach(l => {
            const sub = l.cantidad * l.precio;
            const desc = sub * (l.descuento / 100);

            subtotal += sub;
            descuentos += desc;
        });

        const total = subtotal - descuentos;

        document.getElementById("subtotalGlobal").innerText = "$ " + subtotal.toFixed(2);
        document.getElementById("descuentoGlobal").innerText = "$ " + descuentos.toFixed(2);
        document.getElementById("totalGlobal").innerText = "$ " + total.toFixed(2);
    }

    // 🔹 Agregar línea
    btnAgregar.addEventListener("click", () => {
        lineas.push(crearLinea());
        renderTabla();
    });

    // 🔹 Obtener JSON para backend
    function obtenerJSON() {
        return JSON.stringify(lineas);
    }

    // Inicial
    lineas.push(crearLinea());
    //renderTabla();
</script>