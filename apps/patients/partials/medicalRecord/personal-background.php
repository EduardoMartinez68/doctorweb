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
                <td><input type="radio" name="cronico" value="Neg"></td>
                <td><input type="radio" name="cronico" value="Pos"></td>
                <td><input type="text" class="form-control cronico_com"></td>
                <td></td>
            </tr>

            <tr>
                <td>Traumatismo</td>
                <td><input type="radio" name="trauma" value="Neg"></td>
                <td><input type="radio" name="trauma" value="Pos"></td>
                <td><input type="text" class="form-control trauma_com"></td>
                <td></td>
            </tr>

            <tr>
                <td>Quirúrgicos</td>
                <td><input type="radio" name="quirurgico" value="Neg"></td>
                <td><input type="radio" name="quirurgico" value="Pos"></td>
                <td><input type="text" class="form-control quirurgico_com"></td>
                <td></td>
            </tr>

            <tr>
                <td>Alérgicos</td>
                <td><input type="radio" name="alergico" value="Neg"></td>
                <td><input type="radio" name="alergico" value="Pos"></td>
                <td><input type="text" class="form-control alergico_com"></td>
                <td></td>
            </tr>

            <tr>
                <td>Transfusionales</td>
                <td><input type="radio" name="transfusion" value="Neg"></td>
                <td><input type="radio" name="transfusion" value="Pos"></td>
                <td>
                    <input type="text" class="form-control transfusion_com" placeholder="Detalles">
                </td>
                <td>
                    <input type="date" class="form-control mb-1 transfusion_fecha">
                    <input type="text" class="form-control transfusion_tipo" placeholder="Tipo sanguíneo">
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="section-title">Hábitos y Estilo de Vida</div>

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <label class="form-label">Tabaquismo</label>
        <select class="form-select tabaquismo">
            <option>No</option>
            <option>Ocasional</option>
            <option>Frecuente</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Alcoholismo</label>
        <select class="form-select alcoholismo">
            <option>No</option>
            <option>Ocasional</option>
            <option>Frecuente</option>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Uso de sustancias</label>
        <select class="form-select sustancias">
            <option>No</option>
            <option>Si</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Tipo de sustancias</label>
        <input type="text" class="form-control sustancias_tipo" placeholder="Ej: marihuana, cocaína...">
    </div>

    <div class="col-md-6">
        <label class="form-label">Frecuencia</label>
        <input type="text" class="form-control sustancias_freq" placeholder="Ej: diario, semanal...">
    </div>

    <div class="col-md-6">
        <label class="form-label">Actividad física</label>
        <select class="form-select actividad">
            <option>Nula</option>
            <option>Moderada</option>
            <option>Alta</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Alimentación</label>
        <select class="form-select alimentacion">
            <option>Balanceada</option>
            <option>Regular</option>
            <option>Deficiente</option>
        </select>
    </div>

</div>

</div>