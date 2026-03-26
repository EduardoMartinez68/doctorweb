<!-- Empresa -->
<div class="form-container row">
    <div class="col">
        <!-- Información general -->
        <div class="section-header">Información General del Paciente</div>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="">Nombre del paciente *</label>
                <input type="text" class="form-control" placeholder="Laura Angel..." required name="name">
            </div>
            <div class="col-md-4">
                <label for="">Email</label>
                <input type="email" class="form-control" placeholder="ejemplo@hotmail.com" name="email">
            </div>
            <div class="col-md-2">
                <label for="">Telefono</label>
                <input type="text" class="form-control" placeholder="123456789" name="phone">
            </div>
            <div class="col-md-2">
                <label for="">Celular</label>
                <input type="text" class="form-control" placeholder="4441234567" name="cellphone">
            </div>
        </div>

        <!-- Domicilio -->
        <div class="section-title">Domicilio</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label for="domicilio" class="form-label">Calle / Avenida</label>
                <input type="text" class="form-control" name="domicilio" name="street_address" placeholder="Ej: Av. Insurgentes Sur">
            </div>

            <div class="col-md-2">
                <label for="num_ext" class="form-label">Núm. Ext.</label>
                <input type="text" class="form-control" name="num_ext" name="ext_number" placeholder="Ej: 123">
            </div>

            <div class="col-md-2">
                <label for="num_int" class="form-label">Núm. Int.</label>
                <input type="text" class="form-control" name="num_int" name="int_number" placeholder="Ej: 4B (Opcional)">
            </div>

            <div class="col-md-2">
                <label for="colonia" class="form-label">Colonia</label>
                <input type="text" class="form-control" name="colonia" name="neighborhood" placeholder="Ej: Juárez">
            </div>

            <div class="col-md-4">
                <label for="ciudad" class="form-label">Ciudad</label>
                <input type="text" class="form-control" name="ciudad" name="city" placeholder="Ej: CDMX">
            </div>

            <div class="col-md-4">
                <label for="estado" class="form-label">Estado</label>
                <input type="text" class="form-control" name="state" placeholder="Ej: Ciudad de México">
            </div>

            <div class="col-md-4">
                <label for="codigo_postal" class="form-label">Código Postal</label>
                <input type="text" class="form-control" name="zip_code" placeholder="Ej: 06700">
            </div>
        </div>

        <!-- Estado civil -->
        <div class="section-title">Estado Civil</div>
        <div class="mb-4">
            <select class="form-select" name="marital_status">
                <option>Soltero</option>
                <option>Casado</option>
                <option>Separado</option>
                <option>Divorciado</option>
                <option>Unión libre</option>
                <option>Viudo</option>
            </select>
        </div>

        <!-- Escolaridad -->
        <div class="section-title">Último Grado Estudiado</div>
        <div class="mb-4">
            <select class="form-select" name="level_school">
                <option>Primaria</option>
                <option>Secundaria</option>
                <option>Bachillerato</option>
                <option>Carrera Técnica</option>
                <option>Licenciatura</option>
                <option>Posgrado</option>
            </select>
        </div>
    </div>
</div>