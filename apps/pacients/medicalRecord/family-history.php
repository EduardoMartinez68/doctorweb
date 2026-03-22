<div class="form-container">
<div class="section-header">Antecedentes Heredo-Familiares</div>

<div class="table-responsive mb-3">
    <table class="table table-bordered align-middle" id="tablaFamiliares">
        <thead>
            <tr>
                <th>Familiar</th>
                <th>¿Vive?</th>
                <th>Edad</th>
                <th>¿Sano?</th>
                <th>Padecimientos / Comentarios</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="bodyFamiliares"></tbody>
    </table>
</div>

<button class="btn btn-outline-dark mb-4" onclick="agregarFamiliar()">+ Agregar familiar</button>
</div>

<script>
function agregarFamiliar(data = {}) {
    const tbody = document.getElementById("bodyFamiliares");

    const row = document.createElement("tr");

    row.innerHTML = `
        <td>
            <select class="form-select familiar">
                <option ${data.familiar === "Padre" ? "selected" : ""}>Padre</option>
                <option ${data.familiar === "Madre" ? "selected" : ""}>Madre</option>
                <option ${data.familiar === "Hermano/a" ? "selected" : ""}>Hermano/a</option>
                <option ${data.familiar === "Abuelo/a" ? "selected" : ""}>Abuelo/a</option>
                <option ${data.familiar === "Hijo/a" ? "selected" : ""}>Hijo/a</option>
                <option ${data.familiar === "Otro" ? "selected" : ""}>Otro</option>
            </select>
        </td>

        <td>
            <select class="form-select vive">
                <option ${data.vive === "Si" ? "selected" : ""}>Si</option>
                <option ${data.vive === "No" ? "selected" : ""}>No</option>
            </select>
        </td>

        <td>
            <input type="number" class="form-control edad" value="${data.edad || ''}">
        </td>

        <td>
            <select class="form-select sano">
                <option ${data.sano === "Si" ? "selected" : ""}>Si</option>
                <option ${data.sano === "No" ? "selected" : ""}>No</option>
            </select>
        </td>

        <td>
            <input type="text" class="form-control comentarios" placeholder="Ej: Diabetes, hipertensión..." value="${data.comentarios || ''}">
        </td>

        <td class="text-center">
            <button class="btn btn-sm btn-danger" onclick="eliminarFila(this)">✕</button>
        </td>
    `;

    tbody.appendChild(row);
}
</script>