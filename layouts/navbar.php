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
                <a class="nav-link nav-link-med" href="../../dashboard/views/home.php"><i class="bi bi-house-door"></i>
                    Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../agenda/views/home.php">Agenda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../sales/views/home.php">Ventas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../services/views/home.php">Servicios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../dashboard/views/home.php">Recetas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../patients/views/home.php">Pacientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../files/views/home.php">Archivos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../users/views/home.php">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../dashboard/views/home.php">Permisos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-med" href="../../settings/views/home.php">Configuraciones</a>
            </li>
            <li class="nav-item border-top mt-3 pt-3">
                <a class="nav-link nav-link-med text-danger" href="../../../middleware/logout.php"><i class="bi bi-box-arrow-right"></i>
                     Cerrar Sesión</a>
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
                        Consultas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../../apps/consultation/views/home.php">Ver mis consultas</a></li>
                        <li><a class="dropdown-item" href="../../../apps/consultation/views/form_consultation.php">Crear un Consulta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        ventas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../../apps/sales/views/home.php">Ver mis Ventas</a></li>
                        <li><a class="dropdown-item" href="../../../apps/sales/views/sale.php">Crear una Venta</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Pacientes
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../../apps/patients/views/home.php">ver mis Pacientes</a></li>
                        <li><a class="dropdown-item" href="../../../apps/patients/views/create.php">Crear Paciente Rapido</a></li>
                        <li><a class="dropdown-item" href="../../../apps/patients/views/medical-record.php">Crear expediente Paciente</a></li>
                        <li><a class="dropdown-item" href="../../../apps/patients/views/home_patients_delete.php">Ver Pacientes Eliminados</a></li>
                        
                        <!---
                        <li><a class="dropdown-item" onclick="open_pop_lateral('popPaciente')">Crear Paciente Rapido</a></li>
                        ---->
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Agenda
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../agenda/views/home.php">Ver mi Agenda</a></li>
                        <li><a class="dropdown-item" href="medical-record.php">Crear una Cita</a></li>
                        <li><a class="dropdown-item" href="#">Crear un Evento</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function add_module_to_the_menu(data) {
        /*
        example of the variable data:
            const new_menu = {
                name: 'reports',
                link: '#',
                submenu: {
                    1: {
                        name: 'see sales',
                        link: 'sales.php'
                    },
                    2: {
                        name: 'export PDF',
                        link: '#',
                        funcion: () => console.log("save PDF...")
                    }
                }
            };
        */
        const navbarList = document.querySelector('#plNavbar .navbar-nav');
        if (!navbarList) return;

        // create the element list
        const li = document.createElement('li');
        li.className = 'nav-item';

        // see if have submenus
        const tieneSubmenu = data.submenu && Object.keys(data.submenu).length > 0;

        if (tieneSubmenu) {
            li.classList.add('dropdown');

            // here we will to create the link of the father
            li.innerHTML = `
            <a class="nav-link dropdown-toggle" href="${data.link || '#'}" data-bs-toggle="dropdown">
                ${data.name}
            </a>
            <ul class="dropdown-menu"></ul>
        `;

            const ulSubmenu = li.querySelector('.dropdown-menu');

            // read all the sub-elementos and the added to the menu
            Object.values(data.submenu).forEach(sub => {
                const subLi = document.createElement('li');
                const subA = document.createElement('a');
                subA.className = 'dropdown-item';
                subA.href = sub.link || '#';
                subA.textContent = sub.name;

                //if the menu have a function we will add
                if (sub.funcion) {
                    subA.onclick = (e) => {
                        //if have a link we will to save his link 
                        if (!sub.link || sub.link === '#') e.preventDefault();
                        sub.funcion();
                    };
                }

                subLi.appendChild(subA);
                ulSubmenu.appendChild(subLi);
            });

        } else {
            // when not have submenu we will to create only the menu
            const a = document.createElement('a');
            a.className = 'nav-link';
            a.href = data.link || '#';
            a.textContent = data.name;

            if (data.funcion) {
                a.onclick = (e) => {
                    if (!data.link || data.link === '#') e.preventDefault();
                    data.funcion();
                };
            }
            li.appendChild(a);
        }

        navbarList.appendChild(li);
    }
</script>