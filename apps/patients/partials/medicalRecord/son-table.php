<div class="form-container">

<div class="section-title">Hijos</div>
<div class="table-responsive mb-3">
    <table class="table table-bordered align-middle" id="tablaHijos">
        <thead>
            <tr>
                <th>Género</th>
                <th>Edad</th>
                <th>¿Está sano?</th>
                <th>Observaciones</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="bodyHijos"></tbody>
    </table>
</div>
<button class="btn btn-outline-primary mb-4" onclick="agregarHijo()" type="button">+ Agregar hijo</button>
</div>


<script>
    function agregarHijo(data = {}) {
        const tbody = document.getElementById("bodyHijos");

        const row = document.createElement("tr");

        row.innerHTML = `
        <td>
            <select class="form-select genero">
                <option ${data.genero === "Masculino" ? "selected" : ""}>Masculino</option>
                <option ${data.genero === "Femenino" ? "selected" : ""}>Femenino</option>
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
            <input type="text" class="form-control observaciones" value="${data.observaciones || ''}">
        </td>
        <td class="text-center">
            <button class="btn btn-sm btn-danger" onclick="eliminarFila(this)">✕</button>
        </td>
    `;

        tbody.appendChild(row);
    }

    function eliminarFila(btn) {
        btn.closest("tr").remove();
    }

    function get_children_data() {
        const children = [];
        const rows = document.querySelectorAll("#bodyHijos tr");

        rows.forEach(row => {
            const child = {
                gender: row.querySelector(".genero").value,
                age: parseInt(row.querySelector(".edad").value) || 0,
                is_healthy: row.querySelector(".sano").value === "Si", // Devuelve true si es "Si"
                observations: row.querySelector(".observaciones").value
            };
            children.push(child);
        });

        return children;
    }

    function reset_children_table() {
        const rows = document.querySelectorAll("#bodyHijos tr");

        rows.forEach(row => {
            // 1. Limpiar Selects (Género y Sano)
            const selectGenero = row.querySelector(".genero");
            const selectSano = row.querySelector(".sano");
            
            if(selectGenero) selectGenero.selectedIndex = 0;
            if(selectSano) selectSano.selectedIndex = 0;

            // 2. Limpiar Edad (Input número)
            const inputEdad = row.querySelector(".edad");
            if(inputEdad) inputEdad.value = "";

            // 3. Limpiar Observaciones (Textarea o Input)
            const inputObs = row.querySelector(".observaciones");
            if(inputObs) inputObs.value = "";
        });
    }
</script>