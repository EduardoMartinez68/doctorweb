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