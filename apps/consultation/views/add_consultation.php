<?php
include '../../../middleware/authentication.php';
include '../../../middleware/database.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear consulta Médica</title>
    <?php include '../../../layouts/styles.php'; ?>
    <style>
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: white;
            border-bottom: 2px solid var(--med-bg);
            color: var(--med-primary);
            font-weight: bold;
            border-radius: 12px 12px 0 0 !important;
        }

        .section-title {
            color: var(--text-color-title);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border-left: 4px solid var(--med-primary);
            padding-left: 10px;
        }

        .btn-primary {
            background-color: var(--med-primary);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--med-primary-hover);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--med-secondary);
            box-shadow: 0 0 0 0.25px rgba(56, 182, 255, 0.25);
        }
    </style>

</head>

<body>

    <?php include '../../../layouts/navbar.php'; ?>



    <div class="container py-4">
        <form action="tu_script.php" method="POST">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 style="color: var(--med-primary);">Nueva Consulta Médica</h2>
                <button type="submit" class="btn btn-primary px-4">Guardar Consulta</button>
            </div>

            <div class="card">
                <div class="card-header">1. Signos Vitales (Somatometría)</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Peso (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estatura (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">IMC</label>
                            <input type="number" step="0.01" name="imc" class="form-control" readonly
                                placeholder="Auto">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Temp (°C)</label>
                            <input type="number" step="0.1" name="temperature" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Presión Arterial</label>
                            <input type="text" name="blood_pressure" class="form-control" placeholder="120/80">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">FC (bpm)</label>
                            <input type="number" name="heart_rate" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">FR (rpm)</label>
                            <input type="number" name="respiratory_rate" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Saturación O2 (%)</label>
                            <input type="number" name="oxygen_saturation" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">2. Evaluación Física</div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-2">
                            <label class="form-label">Actitud</label>
                            <select name="actitud" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Habitus</label>
                            <select name="habitus" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Marcha</label>
                            <select name="marcha" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Aparente</label>
                            <select name="aparente" class="form-select">
                                <option value="A">A</option>
                                <option value="M">M</option>
                                <option value="B">B</option>
                            </select>
                        </div>
                    </div>

                    <div class="section-title">Vista</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-2"><label class="form-label">Pupilas</label><select name="pupilas"
                                class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-2"><label class="form-label">Conjuntiva</label><select name="conjuntiva"
                                class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-2"><label class="form-label">OD Sin Lentes</label><input type="text"
                                name="od_sin_lentes" class="form-control form-control-sm"></div>
                        <div class="col-md-2"><label class="form-label">OI Sin Lentes</label><input type="text"
                                name="oi_sin_lentes" class="form-control form-control-sm"></div>
                        <div class="col-12"><label class="form-label">Observaciones Ojos</label><textarea
                                name="observaciones_ojos" class="form-control" rows="2"></textarea></div>
                    </div>

                    <div class="section-title">Cabeza y Cuello</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-2"><label class="form-label">Oídos</label><select name="oidos"
                                class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-2"><label class="form-label">Nariz</label><select name="nariz"
                                class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-2"><label class="form-label">Boca</label><select name="boca"
                                class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-2"><label class="form-label">Tiroides</label><select name="cuello_tiroides"
                                class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-2"><label class="form-label">Ganglios</label><select name="ganglios"
                                class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                    </div>

                    <div class="section-title">Espalda y Columna</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><label class="form-label">Alineación</label><select
                                name="espalda_alineacion" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Puntos Dolorosos</label><select
                                name="puntos_dolorosos" class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Laseague</label><select name="laseague"
                                class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                    </div>

                    <div class="section-title">Tórax y Abdomen</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3"><label class="form-label">Ruidos Cardiacos</label><select
                                name="torax_ruidos_cardiacos" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Abdomen Palpación</label><select
                                name="abdomen_palpacion" class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Visceromegalias</label><select
                                name="abdomen_videromegalias" class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Tumuraciones</label><select
                                name="abdomen_tumuraciones" class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                    </div>

                    <div class="section-title">Piel y Tegumentos</div>
                    <div class="row g-3">
                        <div class="col-md-3"><label class="form-label">Coloración</label><select name="coloracion"
                                class="form-select">
                                <option value="normal">Normal</option>
                                <option value="anormal">Anormal</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Lesiones</label><select name="lesiones"
                                class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Tatuajes</label><select name="tatuajes"
                                class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                        <div class="col-md-3"><label class="form-label">Cicatrices</label><select name="cicatrices"
                                class="form-select">
                                <option value="neg">Negativo</option>
                                <option value="pos">Positivo</option>
                            </select></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">3. Notas de la Consulta</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Motivo de Consulta</label>
                            <textarea name="reason_for_consultation" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sustantivo (Síntomas)</label>
                            <textarea name="symptoms" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Objetivo (Exploración Física Detallada)</label>
                            <textarea name="physical_exploration" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Diagnóstico</label>
                            <textarea name="medical_diagnosis" class="form-control" rows="3"
                                placeholder="CIE-10 o descripción..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Plan de Tratamiento</label>
                            <textarea name="treatment_plan" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado de Consulta</label>
                            <select name="status" class="form-select">
                                <option value="completed">Completada</option>
                                <option value="draft">Borrador</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mb-5">
                <input type="hidden" name="doctor_id" value="1"> <input type="hidden" name="patient_id" value="1">
                <button type="submit" class="btn btn-primary btn-lg px-5">Finalizar y Guardar Consulta</button>
            </div>
        </form>
    </div>





    <?php include '../../../layouts/scripts.php'; ?>

</body>

</html>