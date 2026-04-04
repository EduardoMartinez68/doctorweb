<div class="form-container">
<div class="section-header">Antecedentes Médico - Laborales</div>
<div class="table-responsive mb-3">
    <table class="table table-bordered align-middle" id="tablaLaboral">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Puesto</th>
                <th>Tiempo</th>
                <th>¿Accidentes?</th>
                <th>Exposición a</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="bodyLaboral"></tbody>
    </table>
</div>

<button class="btn btn-outline-primary mb-4" onclick="agregarLaboral()" type="button">+ Agregar registro</button>



<div class="section-title">Comentarios</div>
<div class="mb-4">
    <textarea class="form-control" rows="5"name="note_laboratory"placeholder="Escribe aquí observaciones médicas, antecedentes relevantes o notas del doctor..."></textarea>
</div>
</div>


<script>
    function agregarLaboral(data = {}) {
        const tbody = document.getElementById("bodyLaboral");

        const row = document.createElement("tr");

        row.innerHTML = `
        <td><input type="text" class="form-control empresa" value="${data.empresa || ''}"></td>

        <td><input type="text" class="form-control puesto" value="${data.puesto || ''}"></td>

        <td><input type="text" class="form-control tiempo" placeholder="Ej: 2 años" value="${data.tiempo || ''}"></td>

        <td>
            <select class="form-select accidentes">
                <option ${data.accidentes === "Si" ? "selected" : ""}>Si</option>
                <option ${data.accidentes === "No" ? "selected" : ""}>No</option>
            </select>
        </td>

        <td>
            <select class="form-select exposicion" multiple>
                <option ${data.exposicion?.includes("Ruido") ? "selected" : ""}>Ruido</option>
                <option ${data.exposicion?.includes("Polvo") ? "selected" : ""}>Polvo</option>
                <option ${data.exposicion?.includes("Humo") ? "selected" : ""}>Humo</option>
                <option ${data.exposicion?.includes("Cargas") ? "selected" : ""}>Cargas</option>
                <option ${data.exposicion?.includes("Mov. repetitivos") ? "selected" : ""}>Mov. repetitivos</option>
            </select>
        </td>

        <td class="text-center">
            <button class="btn btn-sm btn-danger" onclick="eliminarFila(this)">✕</button>
        </td>
    `;

        tbody.appendChild(row);
    }

    function get_ccupational_data() {
        const occupationalHistory = [];
        const rows = document.querySelectorAll("#bodyLaboral tr");

        rows.forEach(row => {
            // Lógica para obtener múltiples valores del select de exposición
            const selectedExposures = Array.from(row.querySelector(".exposicion").selectedOptions)
                .map(option => option.value);

            const record = {
                company: row.querySelector(".empresa").value,
                job_title: row.querySelector(".puesto").value,
                duration: row.querySelector(".tiempo").value,
                had_accidents: row.querySelector(".accidentes").value === "Si",
                exposures: selectedExposures
            };
            occupationalHistory.push(record);
        });

        return occupationalHistory
    }


</script>