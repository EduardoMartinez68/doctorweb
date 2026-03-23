<nav class="navbar navbar-expand-lg navbar-odoo shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-menu-toggle me-3" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMedico">
            <span class="menu-icon">☰</span>
        </button>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#plNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="plNavbar">
            <ul class="navbar-nav me-auto">     
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Inicio</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        ventas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="medical-record.php">Ver mis Ventas</a></li>
                        <li><a class="dropdown-item" href="medical-record.php">Crear una Venta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Pacientes
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="medical-record.php">ver mis Pacientes</a></li>
                        <li><a class="dropdown-item"  onclick="open_pop_lateral('popPaciente')">Crear Nuevo Paciente</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Agenda
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="medical-record.php">Ver mi Agenda</a></li>
                        <li><a class="dropdown-item" href="medical-record.php">Crear una Cita</a></li>
                        <li><a class="dropdown-item" href="#">Crear un Evento</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


<pop-lateral name="popPaciente" titulo="Registrar Paciente" size="25%" side="left">
    <form>
        <div class="mb-3">
            <label class="form-label">Nombre del Paciente</label>
            <input type="text" class="form-control" placeholder="Ej. Juan Pérez">
        </div>
        <div class="mb-3">
            <label class="form-label">Notas Médicas</label>
            <textarea class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-success w-100">Guardar</button>
    </form>
</pop-lateral>