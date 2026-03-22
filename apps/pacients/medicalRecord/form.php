<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Médico</title>
    <?php
        include '../main/styles.php';
    ?>
</head>

<body>
    <div class="container py-5">
        <h2>Formulario Médico</h2>
        <div class="form-container">
            <div class="row">
                <div class="col">
                    <div class="section-header">Información de Empresa</div>
                    <div class="mb-4">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Tipo de Centro</label>
                            <select class="form-select">
                                <option>Empleado</option>
                                <option>Outsourcing</option>
                                <option>Sindicalizado</option>
                                <option>Paciente</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Empresa</label>
                            <input type="text" class="form-control" placeholder="Nombre de la empresa">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include 'data-pacient.php';
        include 'son-table.php';
        include 'medical-history.php';
        include 'family-history.php';
        include 'personal-background.php';
        include 'gynecological-and-obstetrical.php';
        ?>

        <!-- Botón -->
        <div class="mt-5 d-grid">
            <button class="btn btn-medical shadow-sm">Guardar Registro Clínico</button>
        </div>

    </div>


    <?php
        include '../main/scripts.php';
    ?>
    <script>



        function get_json_of_the_form() {
            const form = document.getElementById("formMedico");

            const data = {
                fecha: form.querySelector("input[type='date']").value,

                empresa: {
                    tipoCentro: form.querySelector("select").value,
                    nombre: form.querySelector("input[placeholder='Nombre de la empresa']").value
                },

                paciente: {
                    nombre: form.querySelector("input[placeholder='Nombre completo']").value,
                    email: form.querySelector("input[type='email']").value,
                    telefono: form.querySelector("input[placeholder='Teléfono']").value,
                    celular: form.querySelector("input[placeholder='Celular']").value
                },

                domicilio: {
                    direccion: form.querySelector("input[placeholder='Domicilio']").value,
                    numExt: form.querySelector("input[placeholder='Num Ext']").value,
                    numInt: form.querySelector("input[placeholder='Num Int']").value,
                    colonia: form.querySelector("input[placeholder='Colonia']").value,
                    ciudad: form.querySelector("input[placeholder='Ciudad']").value,
                    estado: form.querySelector("input[placeholder='Estado']").value
                },

                estadoCivil: form.querySelectorAll("select")[1].value,
                escolaridad: form.querySelectorAll("select")[2].value,

                hijos: []
            };

            // Recorrer hijos
            const filas = document.querySelectorAll("#bodyHijos tr");

            filas.forEach(fila => {
                data.hijos.push({
                    genero: fila.querySelector(".genero").value,
                    edad: fila.querySelector(".edad").value,
                    sano: fila.querySelector(".sano").value,
                    observaciones: fila.querySelector(".observaciones").value
                });
            });

            data.antecedentesLaborales = [];

            const filasLaborales = document.querySelectorAll("#bodyLaboral tr");

            filasLaborales.forEach(fila => {
                const exposiciones = Array.from(fila.querySelector(".exposicion").selectedOptions)
                    .map(opt => opt.value);

                data.antecedentesLaborales.push({
                    empresa: fila.querySelector(".empresa").value,
                    puesto: fila.querySelector(".puesto").value,
                    tiempo: fila.querySelector(".tiempo").value,
                    accidentes: fila.querySelector(".accidentes").value,
                    exposicion: exposiciones
                });
            });

            // comentarios
            data.comentarios = document.querySelector("textarea").value;


            data.antecedentesFamiliares = [];

            const filasFamiliares = document.querySelectorAll("#bodyFamiliares tr");

            filasFamiliares.forEach(fila => {
                data.antecedentesFamiliares.push({
                    familiar: fila.querySelector(".familiar").value,
                    vive: fila.querySelector(".vive").value,
                    edad: fila.querySelector(".edad").value,
                    sano: fila.querySelector(".sano").value,
                    comentarios: fila.querySelector(".comentarios").value
                });
            });



            data.antecedentesPersonales = {
                cronico: {
                    estado: document.querySelector("input[name='cronico']:checked")?.value || null,
                    comentario: document.querySelector(".cronico_com").value
                },
                traumatismo: {
                    estado: document.querySelector("input[name='trauma']:checked")?.value || null,
                    comentario: document.querySelector(".trauma_com").value
                },
                quirurgicos: {
                    estado: document.querySelector("input[name='quirurgico']:checked")?.value || null,
                    comentario: document.querySelector(".quirurgico_com").value
                },
                alergicos: {
                    estado: document.querySelector("input[name='alergico']:checked")?.value || null,
                    comentario: document.querySelector(".alergico_com").value
                },
                transfusionales: {
                    estado: document.querySelector("input[name='transfusion']:checked")?.value || null,
                    comentario: document.querySelector(".transfusion_com").value,
                    fecha: document.querySelector(".transfusion_fecha").value,
                    tipoSangineo: document.querySelector(".transfusion_tipo").value
                }
            };

            data.habitos = {
                tabaquismo: document.querySelector(".tabaquismo").value,
                alcoholismo: document.querySelector(".alcoholismo").value,
                sustancias: document.querySelector(".sustancias").value,
                sustanciasTipo: document.querySelector(".sustancias_tipo").value,
                sustanciasFrecuencia: document.querySelector(".sustancias_freq").value,
                actividadFisica: document.querySelector(".actividad").value,
                alimentacion: document.querySelector(".alimentacion").value
            };

            data.ginecoObstetricos = {
                menarca: document.querySelector(".menarca").value,
                inicioVidaSexual: document.querySelector(".ivsa").value,
                parejas: document.querySelector(".parejas").value,
                metodoAnticonceptivo: document.querySelector(".anticonceptivo").value,
                fum: document.querySelector(".fum").value,
                ritmoMenstrual: document.querySelector(".ritmo").value,
                gestas: document.querySelector(".gestas").value,
                partos: document.querySelector(".partos").value,
                cesareas: document.querySelector(".cesareas").value,
                abortos: document.querySelector(".abortos").value,
                hijosVivos: document.querySelector(".hijos_vivos").value,
                papanicolaou: document.querySelector(".papanicolaou").value,
                resultadoPapanicolaou: document.querySelector(".papanicolaou_resultado").value,
                observaciones: document.querySelector(".gineco_obs").value
            };
        }
    </script>
</body>

</html>