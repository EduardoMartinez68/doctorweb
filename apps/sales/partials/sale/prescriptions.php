<table class="table align-middle">
    <thead>
        <tr>
            <th>Medicamento</th>
            <th>Presentación (tabletas, jarabe, etc.)</th>
            <th>Dosis</th>
            <th>Indicaciones</th>
            <th>Duración</th>
            <th>Notas</th>
        </tr>
    </thead>
    <tbody id="tablaReceta">
    </tbody>
</table>


<button id="btnAgregarReceta" class="btn btn-link text-primary">
    + Agregar medicamento
</button>

<!-- JSON preview opcional -->
<pre id="jsonReceta"></pre>

<script>
    const tablaReceta = document.getElementById("tablaReceta");
    const btnAgregarReceta = document.getElementById("btnAgregarReceta");

    let receta = [];

    // 🔹 Crear nueva línea de receta
    function crearMedicamento() {
        return {
            medicamento: "",
            presentacion: "",
            dosis: "",
            frecuencia: "",
            duracion: "",
            indicaciones: ""
        };
    }

    // 🔹 Renderizar tabla
    function renderReceta() {
        tablaReceta.innerHTML = "";

        receta.forEach((med, index) => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
            <td>
                <input type="text" class="form-control form-control-sm medicamento" placeholder="Paracetamol" value="${med.medicamento}">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm presentacion" placeholder="Tabletas">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm dosis" placeholder="500mg">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm frecuencia" placeholder="Cada 8 horas">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm duracion" placeholder="7 días">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm indicaciones" placeholder="Después de alimentos">
            </td>
            <td>
                <button class="btn btn-sm text-danger eliminar">🗑</button>
            </td>
        `;

            // Eventos
            tr.querySelector(".medicamento").addEventListener("input", e => {
                receta[index].medicamento = e.target.value;
            });

            tr.querySelector(".presentacion").addEventListener("input", e => {
                receta[index].presentacion = e.target.value;
            });

            tr.querySelector(".dosis").addEventListener("input", e => {
                receta[index].dosis = e.target.value;
            });

            tr.querySelector(".frecuencia").addEventListener("input", e => {
                receta[index].frecuencia = e.target.value;
            });

            tr.querySelector(".duracion").addEventListener("input", e => {
                receta[index].duracion = e.target.value;
            });

            tr.querySelector(".indicaciones").addEventListener("input", e => {
                receta[index].indicaciones = e.target.value;
            });

            tr.querySelector(".eliminar").addEventListener("click", () => {
                receta.splice(index, 1);
                renderReceta();
                actualizarJSONRecetas();
            });

            tablaReceta.appendChild(tr);
        });

        //actualizarJSONRecetas();
    }

    // 🔹 Agregar medicamento
    btnAgregarReceta.addEventListener("click", () => {
        receta.push(crearMedicamento());
        renderReceta();
    });

    // 🔹 Obtener JSON listo para backend
    function obtenerRecetaJSONRecetas() {
        return JSON.stringify(receta);
    }

    // 🔹 Mostrar JSON (debug opcional)
    function actualizarJSONRecetas() {
        document.getElementById("jsonReceta").innerText = JSON.stringify(receta, null, 2);
    }

    // Inicial
    receta.push(crearMedicamento());
    renderReceta();
</script>