<div class="form-container">
    <div class="section-header">Antecedentes Gineco-Obstétricos</div>

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <label class="form-label">Menarca</label>
            <input type="number" class="form-control menarca" name="menarche_age" placeholder="Edad">
        </div>

        <div class="col-md-3">
            <label class="form-label">Inicio vida sexual</label>
            <input type="number" class="form-control ivsa" name="sexual_onset_age" placeholder="Edad">
        </div>

        <div class="col-md-3">
            <label class="form-label">Parejas sexuales</label>
            <input type="number" class="form-control parejas" name="sexual_partners_count">
        </div>

        <div class="col-md-3">
            <label class="form-label">Método anticonceptivo</label>
            <input type="text" class="form-control anticonceptivo" name="contraceptive_method" placeholder="Ej: DIU, pastillas...">
        </div>

        <div class="col-md-3">
            <label class="form-label">FUM (Última menstruación)</label>
            <input type="date" class="form-control fum" name="last_menstrual_period">
        </div>

        <div class="col-md-3">
            <label class="form-label">Ritmo menstrual</label>
            <select class="form-select ritmo" name="menstrual_rhythm">
                <option value="Regular">Regular</option>
                <option value="Irregular">Irregular</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Gestas</label>
            <input type="number" class="form-control gestas" name="pregnancies_count">
        </div>

        <div class="col-md-3">
            <label class="form-label">Partos</label>
            <input type="number" class="form-control partos" name="births_count">
        </div>

        <div class="col-md-3">
            <label class="form-label">Cesáreas</label>
            <input type="number" class="form-control cesareas" name="c_sections_count">
        </div>

        <div class="col-md-3">
            <label class="form-label">Abortos</label>
            <input type="number" class="form-control abortos" name="abortions_count">
        </div>

        <div class="col-md-3">
            <label class="form-label">Hijos vivos</label>
            <input type="number" class="form-control hijos_vivos" name="living_children_count">
        </div>

        <div class="col-md-3">
            <label class="form-label">Papanicolaou</label>
            <select class="form-select papanicolaou" name="pap_smear_done">
                <option value="No">No</option>
                <option value="Si">Si</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Resultado Papanicolaou</label>
            <input type="text" class="form-control papanicolaou_resultado" name="pap_smear_result" placeholder="Resultado o fecha">
        </div>

    </div>

    <div class="mb-4">
        <label class="form-label">Observaciones Gineco-Obstétricas</label>
        <textarea class="form-control gineco_obs" name="ob_gyn_observations" rows="4" placeholder="Ej: irregularidades, tratamientos, antecedentes importantes..."></textarea>
    </div>
</div>