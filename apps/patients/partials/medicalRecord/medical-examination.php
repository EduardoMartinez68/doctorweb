<style>
    .form-check {
        padding: 10px;
        transition: background-color 0.2s;
        border-radius: 5px;
    }

    .form-check:hover {
        background-color: #f8f9fa;
    }

    .hidden {
        display: none;
    }
</style>


<div class="container mt-5 p-4 bg-white shadow-sm rounded">
    <div class="section-header">Examen Médico</div>
    <div class="row">
        <div class="col-4">
            <div class="mb-4">
                <input type="text" id="searchInputMedicalExam" class="form-control form-control-lg"
                    placeholder="Escribe para filtrar padecimientos...">
            </div>
        </div>
        <div class="col-6">

        </div>
    </div>



    <div class="row" id="padecimientosList">
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="chickenpox" name="chickenpox">
                <label class="form-check-label" for="chickenpox">Varicela</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="mumps" name="mumps">
                <label class="form-check-label" for="mumps">Paperas</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="tuberculosis" name="tuberculosis">
                <label class="form-check-label" for="tuberculosis">Tuberculosis</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rubella" name="rubella">
                <label class="form-check-label" for="rubella">Rubéola</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="hepatitis" name="hepatitis">
                <label class="form-check-label" for="hepatitis">Hepatitis</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="clogged_ears" name="clogged_ears">
                <label class="form-check-label" for="clogged_ears">Sensación de oídos tapados</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="hearing_difficulty" name="hearing_difficulty">
                <label class="form-check-label" for="hearing_difficulty">Dificultad para escuchar
                    sonidos</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="ear_pain" name="ear_pain">
                <label class="form-check-label" for="ear_pain">Dolor o sensación en los oídos</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="nasal_obstruction" name="nasal_obstruction">
                <label class="form-check-label" for="nasal_obstruction">Obstrucción en la nariz</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="nasal_discharge" name="nasal_discharge">
                <label class="form-check-label" for="nasal_discharge">Salida de secreción por nariz</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="frequent_sneezing" name="frequent_sneezing">
                <label class="form-check-label" for="frequent_sneezing">Crisis de estornudos frecuentes</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="frequent_cough" name="frequent_cough">
                <label class="form-check-label" for="frequent_cough">Tos frecuente</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="bloody_phlegm" name="bloody_phlegm">
                <label class="form-check-label" for="bloody_phlegm">Flema con sangre</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="asthma_breathing_diff" name="asthma_breathing_diff">
                <label class="form-check-label" for="asthma_breathing_diff">Dificultad para respirar o
                    asma</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="wheezing" name="wheezing">
                <label class="form-check-label" for="wheezing">Silbidos al respirar profundamente</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="fatigue_walking" name="fatigue_walking">
                <label class="form-check-label" for="fatigue_walking">Cansancio fácil al caminar o
                    correr</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="fatigue_stairs" name="fatigue_stairs">
                <label class="form-check-label" for="fatigue_stairs">Cansancio fácil al subir escaleras</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="chest_oppression" name="chest_oppression">
                <label class="form-check-label" for="chest_oppression">Sensación de opresión en el pecho</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="palpitations" name="palpitations">
                <label class="form-check-label" for="palpitations">Palpitaciones</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="chest_pain" name="chest_pain">
                <label class="form-check-label" for="chest_pain">Dolor en el pecho</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="blood_pressure_issues" name="blood_pressure_issues">
                <label class="form-check-label" for="blood_pressure_issues">Presión arterial alta o baja</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="dizziness_blurred_vision"
                    name="dizziness_blurred_vision">
                <label class="form-check-label" for="dizziness_blurred_vision">Mareo, vértigo o visión
                    borrosa</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="varicose_hemorrhoids" name="varicose_hemorrhoids">
                <label class="form-check-label" for="varicose_hemorrhoids">Várices en las piernas o
                    hemorroides</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="intense_vomiting" name="intense_vomiting">
                <label class="form-check-label" for="intense_vomiting">Vómito intenso</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="heartburn_indigestion" name="heartburn_indigestion">
                <label class="form-check-label" for="heartburn_indigestion">Ardor estomacal, agruras o
                    indigestión</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="bloody_stools" name="bloody_stools">
                <label class="form-check-label" for="bloody_stools">Evacuaciones con sangre</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gallbladder_pain" name="gallbladder_pain">
                <label class="form-check-label" for="gallbladder_pain">Dolor o padecimientos de la vesícula
                    biliar</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="fractures_dislocations"
                    name="fractures_dislocations">
                <label class="form-check-label" for="fractures_dislocations">Fracturas, torceduras o
                    luxaciones</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="joint_pain_arthritis" name="joint_pain_arthritis">
                <label class="form-check-label" for="joint_pain_arthritis">Dolores en articulaciones o
                    artritis</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="tendon_injury" name="tendon_injury">
                <label class="form-check-label" for="tendon_injury">Lesión en algún tendón</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="neck_back_pain" name="neck_back_pain">
                <label class="form-check-label" for="neck_back_pain">Dolor en cuello, espalda o cintura</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="painful_urination" name="painful_urination">
                <label class="form-check-label" for="painful_urination">Dolor o ardor al orinar</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="frequent_nocturia" name="frequent_nocturia">
                <label class="form-check-label" for="frequent_nocturia">Despierta frecuentemente a
                    orinar</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="hematuria" name="hematuria">
                <label class="form-check-label" for="hematuria">Sangre o coloración rojiza en la orina</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="dyspareunia" name="dyspareunia">
                <label class="form-check-label" for="dyspareunia">Dificultad o dolor al tener relaciones
                    sexuales</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="penile_discharge" name="penile_discharge">
                <label class="form-check-label" for="penile_discharge">Secreciones anormales por el pene</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="std_history" name="std_history">
                <label class="form-check-label" for="std_history">Enfermedades de transmisión sexual</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="high_risk_sexual_practices"
                    name="high_risk_sexual_practices">
                <label class="form-check-label" for="high_risk_sexual_practices">Prácticas sexuales de
                    riesgo</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="frequent_headache" name="frequent_headache">
                <label class="form-check-label" for="frequent_headache">Dolor de cabeza frecuente</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="body_paralysis" name="body_paralysis">
                <label class="form-check-label" for="body_paralysis">Parálisis en alguna parte del
                    cuerpo</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="seizures" name="seizures">
                <label class="form-check-label" for="seizures">Ataques o convulsiones</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="numbness_limbs" name="numbness_limbs">
                <label class="form-check-label" for="numbness_limbs">Adormecimiento en brazos, manos o
                    piernas</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="skin_hives_itching" name="skin_hives_itching">
                <label class="form-check-label" for="skin_hives_itching">Ronchas o comezón en la piel</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="allergies" name="allergies">
                <label class="form-check-label" for="allergies">Alergia a medicinas, alimentos o
                    sustancias</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="ent_itching" name="ent_itching">
                <label class="form-check-label" for="ent_itching">Comezón o ardor en ojos, nariz o
                    garganta</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="skin_nail_fungus" name="skin_nail_fungus">
                <label class="form-check-label" for="skin_nail_fungus">Hongos en piel o uñas</label>
            </div>
        </div>

        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="weight_change" name="weight_change">
                <label class="form-check-label" for="weight_change">Aumento o disminución importante de
                    peso</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="vision_difficulty" name="vision_difficulty">
                <label class="form-check-label" for="vision_difficulty">Dificultad para ver de cerca o de
                    lejos</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="wears_glasses" name="wears_glasses">
                <label class="form-check-label" for="wears_glasses">Usa lentes</label>
            </div>
        </div>
        <div class="col-md-6 padecimiento-item">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="has_tattoos" name="has_tattoos">
                <label class="form-check-label" for="has_tattoos">Tiene tatuajes</label>
            </div>
        </div>
    </div>
</div>

<div class="form-container">
    <div class="row">
        <div class="col">
            <label for="comments" class="form-label">Comentarios</label>
            <textarea id="comments"name="physical_exam_notes" class="form-control" rows="4" placeholder="Observaciones sobre el examen físico..."></textarea>
        </div>
    </div>
</div>
<script>
    document.getElementById('searchInputMedicalExam').addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        const items = document.querySelectorAll('.padecimiento-item');

        items.forEach(function (item) {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });
</script>



<div class="form-container">
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Actividad Física</label>
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="does_exercise" name="does_exercise" onchange="toggleExercise()">
                <label class="form-check-label" for="does_exercise">
                    ¿Realiza algún tipo de ejercicio?
                </label>
            </div>
            <div class="mb-2">
                <label for="exercise_type" class="form-label">¿Cuál?</label>
                <input type="text" id="exercise_type" name="exercise_type" class="form-control" placeholder="Ej. Natación, Correr" disabled>
            </div>
            <div>
                <label for="exercise_frequency" class="form-label">¿Cada cuánto?</label>
                <input type="text" id="exercise_frequency" name="exercise_frequency" class="form-control" placeholder="Ej. 3 veces por semana">
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">Capacidad Física y Destreza</label>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="safety_shoe_impediment" name="safety_shoe_impediment">
                <label class="form-check-label" for="safety_shoe_impediment">
                    ¿Tiene algún impedimento físico para la utilización de calzado de seguridad?
                </label>
            </div>
            
            <div class="mb-3">
                <label for="dominant_hand" class="form-label">Para trabajar su mano más hábil es la:</label>
                <select class="form-select" id="dominant_hand" name="dominant_hand">
                    <option value="" selected disabled>Seleccione una opción...</option>
                    <option value="right">Derecha</option>
                    <option value="left">Izquierda</option>
                    <option value="ambidextrous">Ambas (Ambidiestro)</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
    // Función para habilitar/deshabilitar el campo de texto según el checkbox
    function toggleExercise() {
        const check = document.getElementById('does_exercise');
        const input = document.getElementById('exercise_type');
        
        if (check.checked) {
            input.disabled = false;
            input.focus();
        } else {
            input.disabled = true;
            input.value = ""; // Limpia el campo si se desmarca
        }
    }

    function get_medical_examination_data() {
        const container = document.getElementById('padecimientosList');
        const checkboxes = container.querySelectorAll('input[type="checkbox"]');

        const data = {};

        checkboxes.forEach(checkbox => {
            data[checkbox.name] = checkbox.checked;
        });

        return data;
    }
</script>