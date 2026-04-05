<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-thin-straight/css/uicons-thin-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-duotone-straight/css/uicons-duotone-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-regular-straight/css/uicons-regular-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
<link rel="icon" href="../../../public/img/logo-doc-blue.ico?v=1.1" type="image/x-icon">
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-brands/css/uicons-brands.css'>

<style>
    :root {
        --med-primary: #004AAD;
        --med-primary-hover: #02377e;
        --text-color-title: #38B6FF;
        --med-bg: #F4F5FF;
        --med-text: #334155;
        --med-border: #e2e8f0;
        --doctor-brand-color: #004AAD;
        --med-primary-online: #004bad9a;
    }

    body {
        background-color: var(--med-bg);
        font-family: 'Inter', sans-serif;
        color: var(--med-text);
    }

    .form-container {
        width: 100%;
        margin: 10px auto;
        background: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .form-control,
    .form-select {
        border: 1px solid var(--med-border);
        border-radius: 8px;
        padding: 12px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--med-primary);
        box-shadow: 0 0 0 4px rgba(0, 168, 168, 0.1);
        outline: none;
    }

    .section-header {
        background: linear-gradient(90deg, #f1f5f9 0%, #ffffff 100%);
        padding: 12px 20px;
        border-left: 4px solid var(--med-primary);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--med-text);
        margin-bottom: 25px;
        border-radius: 0 8px 8px 0;
    }

    .accordion-item {
        border: none;
        margin-bottom: 15px;
    }

    .accordion-button {
        background-color: white !important;
        color: var(--med-text) !important;
        font-weight: 600;
        border: 1px solid var(--med-border) !important;
        border-radius: 12px !important;
        box-shadow: none !important;
    }

    .accordion-button:not(.collapsed) {
        border-bottom: none !important;
        border-bottom-left-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .accordion-body {
        border: 1px solid var(--med-border);
        border-top: none;
        border-radius: 0 0 12px 12px;
        background-color: #fff;
    }

    .btn-medical {
        background-color: var(--med-primary);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: opacity 0.3s;
    }

    .btn-medical:hover {
        opacity: 0.9;
        color: white;
    }

    .btn-medical:hover{
        background-color: var(--med-primary-hover);
    }

    .search-bar { max-width: 400px; }
    .btn-add { border-radius: 50px; }
    .table-container { background: #fff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

    .btn-outline-primary{
        border-color:var(--med-primary);
        color:var(--med-primary);
    }
    .btn-outline-primary:hover{
        background-color:var(--med-primary-online);
        color:white;
        border-color: transparent;
    }

    .btn-search{
        height: 100%;
    }
</style>

<style>
    .navbar-odoo {
        background-color: var(--med-primary);
        padding: 0;
    }

    .navbar-odoo .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        padding: 10px 15px !important;
        font-size: 14px;
        text-transform: capitalize;
    }

    .navbar-odoo .nav-link:hover, .navbar-odoo .nav-item.show .nav-link {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff !important;
    }

    /* Estilo del menú desplegable (Dropdown) */
    .dropdown-menu {
        border-radius: 0; /* Odoo usa esquinas rectas */
        margin-top: 0;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        font-size: 14px;
    }

    .dropdown-item {
        padding: 8px 20px;
    }

    .dropdown-item:hover {
        background-color: #f1f1f1;
        color: var(--med-primary);
    }

    /* Quitar la flechita del dropdown para un look más limpio */
    .dropdown-toggle::after {
        display: none;
    }
</style>


<style>
/* Botón que emula exactamente un input de Bootstrap 5 */
.btn-input-bootstrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #bbbbbb; /* Borde estándar de BS5 */
    border-radius: 0.375rem; /* El redondeo clásico */
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    text-align: left;
    cursor: pointer;
    border-color: #bbbbbb;
}

/* Efecto Hover: Un borde sutilmente más oscuro */
.btn-input-bootstrap:hover {
    border-color: #adb5bd;
    background-color: #fff;
    color: #212529;
}

/* Efecto Focus: El resplandor azul (o el color de tu variable primary) */
.btn-input-bootstrap:focus,
.btn-input-bootstrap:active {
    outline: 0;
    border-color: #d4d4d4; /* Color de foco azul de BS5 */
    background-color: #fff;
}

/* Texto de ayuda (placeholder) */
.btn-input-bootstrap .text-muted-placeholder {
    color: #6c757d;
    opacity: 0.8;
}

/* Icono de búsqueda */
.btn-input-bootstrap i {
    color: #6c757d;
    font-size: 0.9rem;
}
</style>