<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo | Gestión Médica</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        :root {
            --med-primary: #004AAD;
            --med-primary-hover: #02377e;
            --med-secondary: #38B6FF;
            --med-bg: #F4F5FF;
            --med-text: #334155;
            --med-border: #e2e8f0;
        }

        body {
            background-color: var(--med-bg);
            color: var(--med-text);
            font-family: 'Inter', sans-serif;
        }

        .upload-container {
            max-width: 600px;
            margin: 5rem auto;
        }

        .upload-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 74, 173, 0.08);
            text-align: center;
        }

        /* Área de Dropzone */
        .dropzone-area {
            border: 2px dashed var(--med-border);
            border-radius: 15px;
            padding: 3rem 2rem;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .dropzone-area:hover, .dropzone-area.dragover {
            border-color: var(--med-secondary);
            background: rgba(56, 182, 255, 0.05);
        }

        .dropzone-icon {
            font-size: 3rem;
            color: var(--med-secondary);
            margin-bottom: 1rem;
            display: block;
        }

        .dropzone-text {
            font-size: 1.1rem;
            font-weight: 500;
            color: #64748b;
        }

        .file-info {
            display: none;
            margin-top: 1rem;
            padding: 0.8rem;
            background: #e0f2fe;
            border-radius: 10px;
            color: var(--med-primary);
            font-weight: 600;
        }

        /* Botón Estilizado */
        .btn-upload-submit {
            background-color: var(--med-primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.8rem 2rem;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-upload-submit:hover {
            background-color: var(--med-primary-hover);
            box-shadow: 0 5px 15px rgba(0, 74, 173, 0.2);
        }

        .btn-back {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.2s;
        }

        .btn-back:hover {
            color: white;
        }

        #fileInput {
            display: none;
        }
    </style>
</head>
<body>

    <?php include '../../../layouts/navbar.php'; ?>

    <div class="container upload-container">
        <div class="upload-card">
            <div class="mb-4">
                <h3 class="fw-bold" style="color: var(--med-primary);">Subir Documentación</h3>
                <p class="text-muted">Adjunte estudios, imágenes o recetas al servidor médico.</p>
            </div>

            <form id="uploadForm" enctype="multipart/form-data">
                <label for="fileInput" class="dropzone-area" id="dropzone">
                    <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                    <span class="dropzone-text" id="dropzoneText">Arrastra tu archivo aquí o <span style="color: var(--med-secondary);">haz clic para buscar</span></span>
                    <input type="file" name="file" id="fileInput" required>
                    
                    <div id="filePreview" class="file-info">
                        <i class="fas fa-file-medical me-2"></i>
                        <span id="fileNameDisplay"></span>
                    </div>
                </label>

                <button type="submit" class="btn btn-upload-submit shadow-sm">
                    <i class="fas fa-check-circle"></i> Confirmar Subida
                </button>
            </form>

            <div class="mt-4">
                <a href="home.php" class="btn-back">
                    <i class="fas fa-arrow-left me-1"></i> Volver a mis archivos
                </a>
            </div>
        </div>
    </div>

    <?php include '../../../layouts/scripts.php'; ?>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const filePreview = document.getElementById('filePreview');
        const dropzoneText = document.getElementById('dropzoneText');

        // Efecto visual al arrastrar
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            }, false);
        });

        // Mostrar nombre del archivo seleccionado
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                showFileInfo(this.files[0].name);
            }
        });

        // Manejar archivos soltados
        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            if (files.length > 0) {
                showFileInfo(files[0].name);
            }
        });

        function showFileInfo(name) {
            fileNameDisplay.innerText = name;
            filePreview.style.display = 'block';
            dropzoneText.style.display = 'none';
        }

        // Envío del formulario
        document.getElementById('uploadForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Feedback visual de carga
            const btn = e.target.querySelector('button');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Subiendo...';

            const formData = new FormData(e.target);

            try {
                const res = await fetch('../../files/services/upload_file.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        title: '¡Completado!',
                        text: 'El archivo se ha guardado correctamente.',
                        icon: 'success',
                        confirmButtonColor: '#004AAD'
                    });
                    e.target.reset();
                    filePreview.style.display = 'none';
                    dropzoneText.style.display = 'block';
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    </script>
</body>
</html>