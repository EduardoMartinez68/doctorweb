<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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


<script>

    class DynamicSelector extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: 'open' });

            this.page = 1;
            this.search = '';
            this.selected = null;
            this.total_pages = 1;

            this.columns = [];
            this.keys = [];
            this.link = '';
            this.editLink = '';
            this.addLink = '';
        }

        connectedCallback() {
            this.columns = (this.getAttribute('columns') || '').split(',').map(c => c.trim());
            this.keys = (this.getAttribute('keys') || '').split(',').map(k => k.trim());
            this.link = this.getAttribute('link') || '';
            this.editLink = this.getAttribute('edit') || '';
            this.addLink = this.getAttribute('add') || '';

            this.value = this.getAttribute('value') || '';
            this.textValue = this.getAttribute('textValue') || '';

            const name = this.getAttribute('name') || 'item_id';
            let externalInput = this.querySelector(`input[name="${name}"]`);
            if (!externalInput) {
                externalInput = document.createElement('input');
                externalInput.type = 'hidden';
                externalInput.name = name;
                this.appendChild(externalInput); // Se agrega al DOM real (Light DOM)
            }

            this.render();
        }

        getStyles() {
            return `
        <style>
            :host {
                display: block;
                font-family: sans-serif;
                --primary-color: var(--med-primary, #004AAD);
                --hover-color: #f0f4f8;
                --selected-color: #e2e8f0;
                --add-color: #2b642e;
            }

            .selector-display {
                border: 1px solid #ccc;
                padding: 12px;
                border-radius: 6px;
                cursor: pointer;
                background: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
                transition: border-color 0.2s;
            }

            .selector-display:hover {
                border-color: var(--primary-color);
            }

            .modal-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0;
                width: 100vw; height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9999;
                justify-content: center;
                align-items: center;
            }

            .modal-container {
                background: white;
                width: 85%;
                max-width: 900px;
                height: 80vh;
                border-radius: 8px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }

            .modal-header {
                background-color: var(--primary-color);
                color: white;
                padding: 12px 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-header h3 { margin: 0; font-size: 1.1rem; }

            .close-btn {
                background: transparent; border: none; color: white;
                font-size: 1.5rem; cursor: pointer;
            }

            .modal-content {
                padding: 20px;
                overflow-y: auto;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .search-input {
                width: 100%;
                padding: 10px;
                box-sizing: border-box;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 1rem;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            th {
                text-align: left;
                background: #f8f9fa;
                padding: 12px;
                border-bottom: 2px solid #dee2e6;
            }

            td {
                padding: 10px;
                border-bottom: 1px solid #eee;
                cursor: pointer;
            }

            tr:hover td { background: var(--hover-color); }
            tr.selected td { background: var(--selected-color); font-weight: bold; }

            .footer-actions {
                padding: 15px 20px;
                border-top: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .btn-pagination {
                padding: 8px 16px;
                border: 1px solid #ddd;
                background: white;
                cursor: pointer;
                border-radius: 4px;
            }

            .btn-pagination:disabled { opacity: 0.5; cursor: not-allowed; }

            .btn-accept {
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 10px 25px;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
            }
            .edit-btn {
                background: none;
                border: none;
                color: var(--primary-color);
                cursor: pointer;
                font-size: 1.1rem;
                padding: 5px;
                border-radius: 4px;
                transition: background 0.2s;
            }

            .edit-btn:hover {
                background: rgba(0, 74, 173, 0.1);
            }
            .btn-accept:hover { filter: brightness(1.1); }

            .add-btn {
                background: var(--add-color);
                color: white;
                border: none;
                padding: 10px 18px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.85rem;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: filter 0.2s;
                white-space: nowrap;
            }

            .add-btn:hover {
                filter: brightness(1.1);
            }
            /*-----------------------------*/

            .header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: 1.25rem;
                background-color: #ffffff;
                border-radius: 12px 12px 0 0;
                border-bottom: 1px solid #e2e8f0;
            }

            /* Contenedor del buscador con icono */
            .search-wrapper {
                position: relative;
                flex: 1;
                width: 100%;
            }

            .search-icon {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                pointer-events: none;
            }

            .search-input {
                width: 100%;
                padding: 10px 12px 10px 40px;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                font-size: 0.9rem;
                transition: all 0.2s ease;
                outline: none;
            }

            .search-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            /* Grupo de botones */
            .header-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            /* Estilo para el botón de recarga */
            .recharge-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background-color: #f8fafc;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                color: #64748b;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .recharge-btn:hover {
                background-color: #f1f5f9;
                color: #1e293b;
                border-color: #94a3b8;
            }

            .recharge-btn i {
                font-size: 1rem;
                display: inline-block;
                transform: translateY(2px);
            }
        </style>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-regular-straight/css/uicons-regular-straight.css'>
        `;
        }

        render() {
            const addButtonHtml = this.addLink
                ? `<button class="add-btn" id="addBtn">
                <span>+</span> Agregar
               </button>`
                : '';

            const name = this.getAttribute('name') || 'item_id';
            const label = this.getAttribute('label') || 'Seleccionar';
            const title = this.getAttribute('title') || 'Seleccionar';

            this.shadowRoot.innerHTML = `
            ${this.getStyles()}

            <div class="selector-display" id="openBtn">
                <span id="selectedText">${label}</span>
                <span>▼</span>
            </div>

            <div class="modal-overlay" id="modalOverlay">
                <div class="modal-container">
                    <div class="modal-header">
                        <h3>${title}</h3>
                        <button class="close-btn" id="closeBtn">&times;</button>
                    </div>
                    
                    <div class="modal-content">
                        <div class="header">
                            <div class="search-wrapper">
                                <i class="fi fi-rs-search search-icon"></i>
                                <input type="text" class="search-input" id="searchInput" placeholder="Buscar...">
                            </div>
                            
                            <div class="header-actions">
                                ${addButtonHtml}
                                <button class="recharge-btn" id="rechargeBtn" title="Recargar">
                                    <i class="fi fi-rs-rotate-right"></i>
                                </button>
                            </div>
                        </div>

                        
                        
                        <div style="flex-grow: 1; overflow-y: auto;">
                            <table>
                                <thead>
                                    <tr id="tableHead"></tr>
                                </thead>
                                <tbody id="tableBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="footer-actions">
                        <div>
                            <button class="btn-pagination" id="prevBtn">Anterior</button>
                            <span id="pageInfo">Pág. 1</span>
                            <button class="btn-pagination" id="nextBtn">Siguiente</button>
                        </div>
                        <button class="btn-accept" id="acceptBtn">Confirmar Selección</button>
                    </div>
                </div>
            </div>
        `;

            this.renderHeaders();
            this.setupEvents();
        }

        renderHeaders() {
            const head = this.shadowRoot.getElementById('tableHead');
            head.innerHTML = '';

            this.columns.forEach(col => {
                const th = document.createElement('th');
                th.innerText = col;
                head.appendChild(th);
            });

            if (this.editLink) {
                const th = document.createElement('th');
                th.innerText = 'Acciones';
                th.style.textAlign = 'center';
                head.appendChild(th);
            }
        }

        setupEvents() {
            const root = this.shadowRoot;

            root.getElementById('openBtn').onclick = () => this.openModal();
            root.getElementById('closeBtn').onclick = () => this.closeModal();
            root.getElementById('prevBtn').onclick = () => this.changePage(-1);
            root.getElementById('nextBtn').onclick = () => this.changePage(1);
            root.getElementById('acceptBtn').onclick = () => this.acceptSelection();

            //the button for add
            this.addLink = this.getAttribute('add') || '';
            const addBtn = root.getElementById('addBtn');
            if (addBtn && this.addLink) {
                addBtn.onclick = () => {
                    // 1. Verificamos si el string termina en ")" (parece una función)
                    // O si existe como función global en el objeto window
                    if (this.addLink.includes('(') || typeof window[this.addLink] === 'function') {

                        // Opción A: Ejecutar el string como código (si envías parámetros)
                        // Usamos una función anónima para evaluarlo de forma segura
                        new Function(this.addLink)();

                    } else {
                        // 2. Si no parece función, lo tratamos como URL
                        window.location.href = this.addLink;
                    }
                };
            }

            //this is the btn for rechange the table
            const rechargeBtn = root.getElementById('rechargeBtn');
            if (rechargeBtn) {
                rechargeBtn.onclick = () => this.fetchData();
            }

            let timeout;
            root.getElementById('searchInput').oninput = (e) => {
                this.search = e.target.value;
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    //this.search = e.target.value;
                    this.page = 1;
                    this.fetchData();
                }, 300);
            };

            root.getElementById('modalOverlay').onclick = (e) => {
                if (e.target.id === 'modalOverlay') this.closeModal();
            };
        }

        openModal() {
            this.shadowRoot.getElementById('modalOverlay').style.display = 'flex';
            this.fetchData();
        }

        closeModal() {
            this.shadowRoot.getElementById('modalOverlay').style.display = 'none';
        }

        async fetchData() {
            if (!this.link) return;

            try {
                const res = await fetch(`${this.link}?page=${this.page}&search=${this.search}`);
                const response = await res.json();
                this.total_pages = response.total_pages || 1;

                this.renderTable(response.data || []);
                this.updatePaginationUI();
            } catch (err) {
                console.error('Error cargando datos:', err);
            }
        }

        renderTable(data) {
            const tbody = this.shadowRoot.getElementById('tableBody');
            tbody.innerHTML = '';

            data.forEach(item => {
                const tr = document.createElement('tr');
                if (this.selected && this.selected.id === item.id) {
                    tr.classList.add('selected');
                }

                // Celdas de datos
                let html = this.keys.map(key => `<td>${item[key] ?? '-'}</td>`).join('');

                // Celda de edición (si aplica)
                if (this.editLink) {
                    html += `
                <td style="text-align: center;">
                    <button class="edit-btn" data-id="${item.id}" title="Editar">
                        <i class="fi fi-rs-pencil"></i>
                    </button>
                </td>`;
                }

                tr.innerHTML = html;

                // Evento para seleccionar la fila
                tr.onclick = (e) => {
                    // Si el click fue en el botón de editar, no seleccionamos la fila
                    if (e.target.closest('.edit-btn')) return;

                    this.shadowRoot.querySelectorAll('tr').forEach(r => r.classList.remove('selected'));
                    tr.classList.add('selected');
                    this.selected = item;
                };

                // Evento para el botón de editar
                if (this.editLink) {
                    const btn = tr.querySelector('.edit-btn');
                    btn.onclick = (e) => {
                        e.stopPropagation(); // Evita que se dispare el onclick del tr
                        this.handleEdit(item.id);
                    };
                }

                tbody.appendChild(tr);
            });
        }

        handleEdit(id) {
            if (!this.editLink) return;

            // Si es una función (ej: update_data_patient_pop_flash)
            if (typeof window[this.editLink] === 'function') {
                window[this.editLink](id);
            }
            // Si contiene paréntesis (ej: openPop('view', ...))
            else if (this.editLink.includes('(')) {
                // Reemplazamos o inyectamos el ID si es necesario, 
                // o simplemente ejecutamos el string asumiendo que la función sabe qué hacer
                new Function('id', this.editLink)(id);
            }
            // Si es una URL
            else {
                window.location.href = `${this.editLink}?id=${id}`;
            }
        }
        
        updatePaginationUI() {
            const root = this.shadowRoot;

            root.getElementById('pageInfo').innerText =
                `Pág. ${this.page} de ${this.total_pages}`;

            root.getElementById('prevBtn').disabled = this.page <= 1;
            root.getElementById('nextBtn').disabled = this.page >= this.total_pages;
        }

        changePage(step) {
            this.page += step;
            this.fetchData();
        }

        acceptSelection() {
            const hidden = this.shadowRoot.getElementById('hiddenInput');
            const idValue = this.selected.id ?? '';

            if (!this.selected) {
                hidden.value = '';
                return;
            }

            const name = this.getAttribute('name');
            const externalInput = this.querySelector(`input[name="${name}"]`);
            if (externalInput) {
                externalInput.value = idValue;
            }

            // 🔥 texto visible basado en columnas (ej: Nombre - Email)
            const text = this.keys
                .map(k => this.selected[k])
                .filter(Boolean)
                .join(' - ');

            this.shadowRoot.getElementById('selectedText').innerText = text;

            this.dispatchEvent(new CustomEvent('item-selected', {
                detail: this.selected,
                bubbles: true,
                composed: true
            }));

            this.closeModal();
        }
    
        setValue(id, text) {
            const name = this.getAttribute('name') || 'item_id';
            const label = this.getAttribute('label') || 'Seleccionar';

            // 1. Actualizar el input oculto en el Light DOM
            const externalInput = this.querySelector(`input[name="${name}"]`);
            if (externalInput) {
                externalInput.value = id;
                // Disparamos el evento 'change' por si tienes listeners externos (como el de ocultar el botón cancelar)
                externalInput.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // 2. Actualizar el texto visible en el Shadow DOM
            const displayLabel = this.shadowRoot.getElementById('selectedText');
            if (displayLabel) {
                displayLabel.innerText = text || label;
            }

            // 3. Sincronizar el objeto 'selected' interno por si se consulta después
            // Creamos un objeto mínimo con el ID para mantener consistencia
            this.selected = { id: id };
        }

        getValue(){
            const name = this.getAttribute('name') || 'item_id';
            const externalInput = this.querySelector(`input[name="${name}"]`);
            if (externalInput) {
                return externalInput.value;
            }

            return null;
        }
    }

    customElements.define('dynamic-selector', DynamicSelector);
</script>


<script>
    class DynamicTable extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: 'open' });

            this.page = 1;
            this.search = '';
            this.total_pages = 1;

            this.columns = [];
            this.keys = [];
            this.link = '';
            this.editLink = '';
            this.addLink = '';
        }

        connectedCallback() {
            this.columns = (this.getAttribute('columns') || '').split(',').map(c => c.trim());
            this.keys = (this.getAttribute('keys') || '').split(',').map(k => k.trim());
            this.link = this.getAttribute('link') || '';
            this.editLink = this.getAttribute('edit') || '';
            this.addLink = this.getAttribute('add') || '';

            this.render();
            this.fetchData();
        }

        getStyles() {
            return `
        <style>
            :host {
                display: block;
                font-family: sans-serif;
                --primary-color: #004AAD;
                --add-color: #2b642e;
                --hover-row: #f8fafc;
                --border-color: #e2e8f0;
            }

            .container {
                border: 1px solid #ddd;
                border-radius: 8px;
                overflow: hidden;
                background: white;
            }

            .recharge-btn{
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 10px 18px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.85rem;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: filter 0.2s;
                white-space: nowrap;
            }

            .search-icon {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                pointer-events: none;
            }
                
            .search-container {
                flex-grow: 1;
                max-width: 400px;
            }

            .add-btn {
                background: var(--add-color);
                color: white;
                border: none;
                padding: 10px 18px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.85rem;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: filter 0.2s;
                white-space: nowrap;
            }

            .add-btn:hover {
                filter: brightness(1.1);
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }

            th {
                background: #f8f9fa;
                padding: 12px;
                text-align: left;
            }

            td {
                padding: 10px;
                border-top: 1px solid #eee;
            }

            tr:hover td {
                background: #f5f5f5;
            }

            .edit-btn {
                background: var(--primary-color);
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 4px;
                cursor: pointer;
            }

            .footer-actions {
                padding: 15px 20px;
                border-top: 1px solid #eee;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .btn-pagination {
                padding: 8px 16px;
                border: 1px solid #ddd;
                background: white;
                cursor: pointer;
                border-radius: 4px;
            }

            .btn-pagination:disabled { opacity: 0.5; cursor: not-allowed; }

            .header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: 1.25rem;
                background-color: #ffffff;
                border-radius: 12px 12px 0 0;
                border-bottom: 1px solid #e2e8f0;
            }

            /* Contenedor del buscador con icono */
            .search-wrapper {
                position: relative;
                flex: 1;
                width: 100%;
            }

            .search-icon {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                pointer-events: none;
            }

            .search-input {
                width: 50%;
                padding: 10px 12px 10px 40px;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                font-size: 0.9rem;
                transition: all 0.2s ease;
                outline: none;
            }

            .search-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            /* Grupo de botones */
            .header-actions {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            /* Estilo para el botón de recarga */
            .recharge-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                background-color: #f8fafc;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                color: #64748b;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .recharge-btn:hover {
                background-color: #f1f5f9;
                color: #1e293b;
                border-color: #94a3b8;
            }

            .recharge-btn i {
                font-size: 1rem;
                display: inline-block;
                transform: translateY(2px);
            }


        </style>
        <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/4.0.0/uicons-regular-straight/css/uicons-regular-straight.css'>
        `;
        }

        render() {
            const addButtonHtml = this.addLink
                ? `<button class="add-btn" id="addBtn">
                <span>+</span> Agregar
               </button>`
                : '';

            this.shadowRoot.innerHTML = `
            ${this.getStyles()}
            <div class="container">
                <div class="header">
                    <div class="search-wrapper">
                        <i class="fi fi-rs-search search-icon"></i>
                        <input type="text" class="search-input" placeholder="Buscar registros..." id="searchInput">
                    </div>
                    
                    <div class="header-actions">
                        ${addButtonHtml}
                        <button class="recharge-btn" id="rechargeBtn" title="Recargar">
                            <i class="fi fi-rs-rotate-right"></i>
                        </button>
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr id="thead"></tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                </div>

                <div class="footer-actions">
                    <span id="pageInfo">Pág. 1</span>
                    <div>
                        <button class="btn-pagination" id="prevBtn">Anterior</button>
                        <button class="btn-pagination" id="nextBtn">Siguiente</button>
                    </div>
                </div>
            </div>
        `;

            this.renderHeaders();
            this.setupEvents();
        }

        renderHeaders() {
            const thead = this.shadowRoot.getElementById('thead');
            thead.innerHTML = '';

            this.columns.forEach(col => {
                const th = document.createElement('th');
                th.innerText = col;
                thead.appendChild(th);
            });

            if (this.editLink) {
                const th = document.createElement('th');
                th.innerText = 'Acciones';
                thead.appendChild(th);
            }
        }

        setupEvents() {
            const root = this.shadowRoot;

            root.getElementById('prevBtn').onclick = () => this.changePage(-1);
            root.getElementById('nextBtn').onclick = () => this.changePage(1);

            //the button for add
            const addBtn = root.getElementById('addBtn');
            if (addBtn) {
                if (typeof this.addLink === 'function') {
                    // Si es una función, la ejecutamos
                    addBtn.onclick = () => this.addLink();
                } else if (typeof this.addLink === 'string') {
                    // Si es un string, lo tratamos como URL
                    addBtn.onclick = () => window.location.href = this.addLink;
                }
            }

            //this is the btn for rechange the table
            const rechargeBtn = root.getElementById('rechargeBtn');
            if (rechargeBtn) {
                rechargeBtn.onclick = () => this.fetchData();
            }


            let timeout;
            root.getElementById('searchInput').oninput = (e) => {
                this.search = e.target.value;
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.page = 1;
                    this.fetchData();
                }, 300);
            };
        }

        async fetchData() {
            if (!this.link) return;

            try {
                const res = await fetch(`${this.link}?page=${this.page}&search=${this.search}`);
                const response = await res.json();

                this.total_pages = response.total_pages || 1;

                this.renderTable(response.data || []);
                this.updatePagination();
            } catch (err) {
                console.error(err);
            }
        }

        renderTable(data) {
            const tbody = this.shadowRoot.getElementById('tbody');
            tbody.innerHTML = '';

            data.forEach(item => {
                const tr = document.createElement('tr');

                // columnas dinámicas
                tr.innerHTML = this.keys.map(k => `<td>${item[k] ?? '-'}</td>`).join('');

                // botón editar
                if (this.editLink) {
                    const td = document.createElement('td');

                    const btn = document.createElement('button');
                    btn.className = 'edit-btn';
                    btn.innerText = 'Editar';

                    btn.onclick = () => {
                        const url = `${this.editLink}${item.id}`;
                        window.location.href = url;
                    };

                    td.appendChild(btn);
                    tr.appendChild(td);
                }

                tbody.appendChild(tr);
            });
        }

        updatePagination() {
            const root = this.shadowRoot;

            root.getElementById('pageInfo').innerText =
                `Pág. ${this.page} de ${this.total_pages}`;

            root.getElementById('prevBtn').disabled = this.page <= 1;
            root.getElementById('nextBtn').disabled = this.page >= this.total_pages;
        }

        changePage(step) {
            this.page += step;
            this.fetchData();
        }
    }

    customElements.define('dynamic-table', DynamicTable);
</script>