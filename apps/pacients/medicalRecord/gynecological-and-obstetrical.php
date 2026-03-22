<div class="form-container">
<div class="section-header">Antecedentes Gineco-Obstétricos</div>

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <label class="form-label">Menarca</label>
        <input type="number" class="form-control menarca" placeholder="Edad">
    </div>

    <div class="col-md-3">
        <label class="form-label">Inicio vida sexual</label>
        <input type="number" class="form-control ivsa" placeholder="Edad">
    </div>

    <div class="col-md-3">
        <label class="form-label">Parejas sexuales</label>
        <input type="number" class="form-control parejas">
    </div>

    <div class="col-md-3">
        <label class="form-label">Método anticonceptivo</label>
        <input type="text" class="form-control anticonceptivo" placeholder="Ej: DIU, pastillas...">
    </div>

    <div class="col-md-3">
        <label class="form-label">FUM (Última menstruación)</label>
        <input type="date" class="form-control fum">
    </div>

    <div class="col-md-3">
        <label class="form-label">Ritmo menstrual</label>
        <select class="form-select ritmo">
            <option>Regular</option>
            <option>Irregular</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Gestas</label>
        <input type="number" class="form-control gestas">
    </div>

    <div class="col-md-3">
        <label class="form-label">Partos</label>
        <input type="number" class="form-control partos">
    </div>

    <div class="col-md-3">
        <label class="form-label">Cesáreas</label>
        <input type="number" class="form-control cesareas">
    </div>

    <div class="col-md-3">
        <label class="form-label">Abortos</label>
        <input type="number" class="form-control abortos">
    </div>

    <div class="col-md-3">
        <label class="form-label">Hijos vivos</label>
        <input type="number" class="form-control hijos_vivos">
    </div>

    <div class="col-md-3">
        <label class="form-label">Papanicolaou</label>
        <select class="form-select papanicolaou">
            <option>No</option>
            <option>Si</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Resultado Papanicolaou</label>
        <input type="text" class="form-control papanicolaou_resultado" placeholder="Resultado o fecha">
    </div>

</div>

<div class="mb-4">
    <label class="form-label">Observaciones Gineco-Obstétricas</label>
    <textarea 
        class="form-control gineco_obs" 
        rows="4" 
        placeholder="Ej: irregularidades, tratamientos, antecedentes importantes...">
    </textarea>
</div>
</div>