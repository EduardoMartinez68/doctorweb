<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    class PopLateral extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: 'open' });
        }

        connectedCallback() {
            const id = this.getAttribute('name');
            const titulo = this.getAttribute('titulo') || 'Panel';
            const size = this.getAttribute('size') || '30%';
            const side = this.getAttribute('side') || 'right'; // right o left

            this.shadowRoot.innerHTML = `
        <style>
            :host { --ancho: ${size}; }
            .overlay {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(0,0,0,0.5); display: none; z-index: 1050;
                transition: opacity 0.3s ease;
            }
            .panel {
                position: fixed; top: 0; ${side}: -${size}; width: var(--ancho); height: 100%;
                background: white; box-shadow: -2px 0 10px rgba(0,0,0,0.1);
                transition: all 0.3s ease-in-out; z-index: 1060;
                display: flex; flex-direction: column; font-family: sans-serif;
            }
            .panel.active { ${side}: 0; }
            .header {
                padding: 15px; border-bottom: 1px solid #eee;
                display: flex; justify-content: space-between; align-items: center;
                background: #004AAD;
                color:white;
                font-family: 'Inter', sans-serif;
                font-size:1.3rem;
            }
            .content { padding: 20px; flex-grow: 1; overflow-y: auto; }
            .close-btn { 
                cursor: pointer; background: none; border: none; font-size: 1.5rem; 
                line-height: 1; color: white;
            }
            .close-btn:hover { color: #e7e7e7; }
        </style>
        
        <div class="overlay" id="overlay"></div>
        <div class="panel" id="panel">
            <div class="header">
                <h5 style="margin:0">${titulo}</h5>
                <button class="close-btn" id="close">&times;</button>
            </div>
            <div class="content">
                <slot></slot> </div>
        </div>
        `;

            this.overlay = this.shadowRoot.getElementById('overlay');
            this.panel = this.shadowRoot.getElementById('panel');

            this.shadowRoot.getElementById('close').onclick = () => this.close();
            this.overlay.onclick = () => this.close();
        }

        open() {
            this.overlay.style.display = 'block';
            setTimeout(() => this.panel.classList.add('active'), 10);
        }

        close() {
            this.panel.classList.remove('active');
            setTimeout(() => {
                this.overlay.style.display = 'none';
                // Lógica de reinicio de formulario
                const form = this.querySelector('form');
                if (form) form.reset();
            }, 300);
        }
    }

    customElements.define('pop-lateral', PopLateral);

    // Función global para llamar desde cualquier botón
    function open_pop_lateral(name) {
        const el = document.querySelector(`pop-lateral[name="${name}"]`);
        if (el) el.open();
    }
</script>
<script>
class PlusPop extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({ mode: 'open' });
    }

    static get observedAttributes() {
        return ['width', 'height', 'title'];
    }

    connectedCallback() {
        this.render();
    }

    // Método para cerrar el popup
    close() {
        this.style.display = 'none';
    }

    // Método para abrir el popup
    open() {
        this.style.display = 'flex';
    }

    render() {
        const title = this.getAttribute('title') || 'Información';
        const width = this.getAttribute('width') || '80%';
        const height = this.getAttribute('height') || 'auto';

        this.shadowRoot.innerHTML = `
        <style>
            :host {
                display: none; /* Oculto por defecto */
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9999;
                justify-content: center;
                align-items: center;
                font-family: sans-serif;
            }

            .modal-container {
                background: white;
                width: ${width};
                height: ${height};
                max-width: 95%;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
                display: flex;
                flex-direction: column;
            }

            .modal-header {
                background-color: var(--med-primary, #004AAD);
                color: white;
                padding: 12px 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-header h3 {
                margin: 0;
                font-size: 1.1rem;
            }

            .close-btn {
                background: transparent;
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
                line-height: 1;
            }

            .modal-content {
                padding: 20px;
                overflow-y: auto;
                flex-grow: 1;
            }
        </style>
        
        <div class="modal-container">
            <div class="modal-header">
                <h3>${title}</h3>
                <button class="close-btn" id="closeBtn">&times;</button>
            </div>
            <div class="modal-content">
                <slot></slot> </div>
        </div>
        `;

        this.shadowRoot.getElementById('closeBtn').onclick = () => this.close();
    }
}

// Registrar el componente
customElements.define('plus-pop', PlusPop);

/**
 * Funciones globales para controlar los popups por su atributo 'name'
 */
function openPop(name) {
    const pop = document.querySelector(`plus-pop[name="${name}"]`);
    if (pop) pop.open();
}

function closePop(name) {
    const pop = document.querySelector(`plus-pop[name="${name}"]`);
    if (pop) pop.close();
}
</script>
<script>
    /**
     * Inicializa una tabla dinámica con búsqueda, paginación y selección.
     * @param {string} tbodyId - ID del <tbody> de la tabla.
     * @param {string} inputId - ID del <input> buscador.
     * @param {string} endpoint - URL del backend.
     * @param {string} paginationContainerId - ID del div donde irán los botones.
     * @param {Function} onSelect - Callback que recibe los datos al aceptar.
     */
    async function initDynamicTable(tbodyId, inputId, endpoint, paginationContainerId, onSelect) {
        let currentPage = 1;
        let searchTerm = "";

        const tbody = document.getElementById(tbodyId);
        const input = document.getElementById(inputId);
        const paginationContainer = document.getElementById(paginationContainerId);

        // 1. Función principal de carga
        async function fetchData(page = 1, search = "") {
            try {
                // Construcción de la URL con parámetros (ajustar según tu API)
                const url = new URL(endpoint);
                url.searchParams.append('page', page);
                url.searchParams.append('query', search);

                const response = await fetch(url);
                const data = await response.json();

                // Renderizado
                renderTable(data.items); // 'items' es el array de datos
                renderPagination(data.pagination); // 'pagination' contiene totalPages, etc.
            } catch (error) {
                console.error("Error cargando datos:", error);
            }
        }

        // 2. Renderizado de filas
        function renderTable(items) {
            tbody.innerHTML = "";
            items.forEach(item => {
                const row = document.createElement('tr');
                row.classList.add('hover:bg-gray-100', 'cursor-pointer');

                // Renderizado dinámico de celdas (puedes ajustar qué campos mostrar)
                // Aquí usamos Object.values para ejemplo, o mapeo específico
                row.innerHTML = `
                ${Object.values(item).map(val => `<td>${val}</td>`).join('')}
                <td>
                    <button class="select-btn btn-primary" data-id="${item.id}">
                        Aceptar
                    </button>
                </td>
            `;

                // Evento de selección
                row.querySelector('.select-btn').addEventListener('click', () => {
                    onSelect(item);
                });

                tbody.appendChild(row);
            });
        }

        // 3. Lógica de Paginación
        function renderPagination(info) {
            paginationContainer.innerHTML = "";

            const prevBtn = document.createElement('button');
            prevBtn.innerText = "Anterior";
            prevBtn.disabled = !info.hasPrevious;
            prevBtn.onclick = () => { currentPage--; fetchData(currentPage, searchTerm); };

            const nextBtn = document.createElement('button');
            nextBtn.innerText = "Siguiente";
            nextBtn.disabled = !info.hasNext;
            nextBtn.onclick = () => { currentPage++; fetchData(currentPage, searchTerm); };

            paginationContainer.append(prevBtn, ` Página ${info.currentPage} de ${info.totalPages} `, nextBtn);
        }

        // 4. Listener del Buscador (con Debounce para no saturar el server)
        let timeout;
        input.addEventListener('input', (e) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                searchTerm = e.target.value;
                currentPage = 1; // Reiniciar a la primera página tras buscar
                fetchData(currentPage, searchTerm);
            }, 500);
        });

        // Carga inicial
        fetchData();
    }
</script>