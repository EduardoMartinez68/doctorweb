<div class="form-container">
    <div class="row">
        <div class="col">
            <div class="section-header">Información de Empresa</div>

            <div class="mb-4">
                <label class="form-label">Fecha</label>
                <input type="date" class="form-control" name="company_date">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tipo de Centro</label>
                    <select class="form-select" name="company_center_type">
                        <option value="0">Empleado</option>
                        <option value="1">Outsourcing</option>
                        <option value="2">Sindicalizado</option>
                        <option value="3">Paciente</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Empresa</label>
                    <input type="text" class="form-control" name="company_name" placeholder="Nombre de la empresa">
                </div>
            </div>
        </div>
    </div>
</div>