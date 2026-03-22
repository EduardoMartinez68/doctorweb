<div class="offcanvas offcanvas-start border-0 shadow" tabindex="-1" id="sidebarMedico" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center">
            <div class="bg-primary rounded-circle me-2"
                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                <i class="fi fi-ss-user-md"></i>
            </div>
            <h5 class="offcanvas-title fw-bold" id="sidebarLabel">Doctor<span
                    class="text-primary text-opacity-75">Web</span></h5>
        </div>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <div class="user-profile mb-4 p-3 bg-light rounded-3 d-flex align-items-center">
            <div class="avatar me-3">
                <img src="https://ui-avatars.com/api/?name=Dr+Suarez&background=00a8a8&color=fff" class="rounded-circle"
                    width="45" alt="Doctor">
            </div>
            <div>
                <p class="mb-0 fw-bold small text-dark">Dr. Suarez</p>
                <span class="text-muted small">Cardiólogo</span>
            </div>
        </div>

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#"><i class="bi bi-house-door"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#">Agenda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#">Ventas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#">Recetas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#">Pacientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#">Permisos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="#">Configuraciones</a>
            </li>
            <li class="nav-item border-top mt-3 pt-3">
                <a class="nav-link nav-link-med text-danger" href="#">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    /* Estilos para el Sidebar Médico */
    .offcanvas {
        background-color: #ffffff;
        width: 280px !important;
    }

    .btn-medical-menu {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        z-index: 1050;
        /* Por encima de todo */
        transition: all 0.3s ease;
    }

    .btn-medical-menu:hover {
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .nav-link-med {
        color: #64748b;
        padding: 12px 15px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
        margin-bottom: 5px;
        text-decoration: none;
        display: block;
    }

    .nav-link-med:hover {
        background-color: #f1f5f9;
        color: #004AAD;
    }

    .nav-link-med.active {
        background-color: #004AAD;
        color: white !important;
    }
</style>

<script>
    // Lógica para detectar la tecla "Escape"
    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape") {
            const sidebarElement = document.getElementById('sidebarMedico');
            const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(sidebarElement);

            // Si está abierto lo cierra, si está cerrado lo abre
            if (sidebarElement.classList.contains('show')) {
                bsOffcanvas.hide();
            } else {
                bsOffcanvas.show();
            }
        }
    });
</script>


<nav class="navbar doctor-web-nav fixed-top shadow-sm">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="btn btn-menu-toggle me-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMedico">
                <span class="menu-icon">☰</span>
            </button>

            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                Doctor<span class="fw-light">Web</span>
            </a>
        </div>

        <div class="d-none d-md-block text-white-50 small">
            Panel de Control Médico v1.0
        </div>
    </div>
</nav>

<style>
    /* Clase única para evitar conflictos con otros navbars */
    .doctor-web-nav {
        background-color: var(--doctor-brand-color) !important;
        height: 65px;
        z-index: 1040;
        /* Justo debajo del Offcanvas para que no lo tape */
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    /* Estilo del botón de menú */
    .btn-menu-toggle {
        background-color: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 5px 15px;
        transition: all 0.2s ease;
    }

    .btn-menu-toggle:hover {
        background-color: rgba(255, 255, 255, 0.25);
        color: white;
        transform: scale(1.05);
    }

    .menu-icon {
        font-size: 1.2rem;
    }

    /* Estilo del Logo/Texto */
    .doctor-web-nav .navbar-brand {
        color: white !important;
        font-family: 'Inter', sans-serif;
        letter-spacing: -0.5px;
        font-size: 1.4rem;
    }

    .brand-dot {
        width: 10px;
        height: 10px;
        background-color: #fff;
        border-radius: 50%;
        display: inline-block;
        margin-right: 10px;
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }
</style>