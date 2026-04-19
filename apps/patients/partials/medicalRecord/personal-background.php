<div class="form-container">
<div class="section-header">Antecedentes Personales</div>

<div class="table-responsive mb-4">
    <table class="table table-bordered align-middle" id="tablaPersonales">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Neg</th>
                <th>Pos</th>
                <th>Comentario</th>
                <th>Extra</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Crónico Degenerativo</td>
                <td><input type="radio" name="personal_chronic" value="Neg"></td>
                <td><input type="radio" name="personal_chronic" value="Pos"></td>
                <td><input type="text" class="form-control" name="personal_chronic_comment"></td>
                <td></td>
            </tr>

            <tr>
                <td>Traumatismo</td>
                <td><input type="radio" name="personal_trauma" value="Neg"></td>
                <td><input type="radio" name="personal_trauma" value="Pos"></td>
                <td><input type="text" class="form-control" name="personal_trauma_comment"></td>
                <td></td>
            </tr>

            <tr>
                <td>Quirúrgicos</td>
                <td><input type="radio" name="personal_surgery" value="Neg"></td>
                <td><input type="radio" name="personal_surgery" value="Pos"></td>
                <td><input type="text" class="form-control" name="personal_surgery_comment"></td>
                <td></td>
            </tr>

            <tr>
                <td>Alérgicos</td>
                <td><input type="radio" name="personal_allergy" value="Neg"></td>
                <td><input type="radio" name="personal_allergy" value="Pos"></td>
                <td><input type="text" class="form-control" name="personal_allergy_comment"></td>
                <td></td>
            </tr>

            <tr>
                <td>Transfusionales</td>
                <td><input type="radio" name="personal_transfusion" value="Neg"></td>
                <td><input type="radio" name="personal_transfusion" value="Pos"></td>
                <td>
                    <input type="text" class="form-control" name="personal_transfusion_comment" placeholder="Detalles">
                </td>
                <td>
                    <input type="date" class="form-control mb-1" name="personal_transfusion_date">
                    <input type="text" class="form-control" name="personal_transfusion_type" placeholder="Tipo sanguíneo">
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="section-title">Hábitos y Estilo de Vida</div>

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <label class="form-label">Tabaquismo</label>
        <select class="form-select" name="lifestyle_smoking">
            <option value="No">No</option>
            <option value="Ocasional">Ocasional</option>
            <option value="Frecuente">Frecuente</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Alcoholismo</label>
        <select class="form-select" name="lifestyle_alcohol">
            <option value="No">No</option>
            <option value="Ocasional">Ocasional</option>
            <option value="Frecuente">Frecuente</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Uso de sustancias</label>
        <select class="form-select" name="lifestyle_drugs">
            <option value="No">No</option>
            <option value="Si">Si</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Tipo de sustancias</label>
        <input type="text" class="form-control" name="lifestyle_drugs_type" placeholder="Ej: marihuana, cocaína...">
    </div>

    <div class="col-md-6">
        <label class="form-label">Frecuencia</label>
        <input type="text" class="form-control" name="lifestyle_drugs_frequency" placeholder="Ej: diario, semanal...">
    </div>

    <div class="col-md-6">
        <label class="form-label">Actividad física</label>
        <select class="form-select" name="lifestyle_activity">
            <option value="Nula">Nula</option>
            <option value="Moderada">Moderada</option>
            <option value="Alta">Alta</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Alimentación</label>
        <select class="form-select" name="lifestyle_diet">
            <option value="Balanceada">Balanceada</option>
            <option value="Regular">Regular</option>
            <option value="Deficiente">Deficiente</option>
        </select>
    </div>

</div>

</div>
