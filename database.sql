/*--------------------------------------USERS---------------------------------------------*/
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    photo LONGBLOB,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    
    phone VARCHAR(20),
    cellphone VARCHAR(20),
    role ENUM('user', 'doctor', 'admin') DEFAULT 'admin',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE clinic (
    id INT AUTO_INCREMENT PRIMARY KEY,

    logo LONGBLOB,
    name VARCHAR(150) NOT NULL,
    
    phone VARCHAR(20),
    cellphone VARCHAR(20),
    email VARCHAR(150),
    
    address VARCHAR(255),
    
    -- 🌍 settings global
    currency VARCHAR(10) DEFAULT 'MXN',
    timezone VARCHAR(50) DEFAULT 'America/Mexico_City',
    date_format VARCHAR(20) DEFAULT 'YYYY-MM-DD',
    time_format VARCHAR(20) DEFAULT '24h',


    opening_hours TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


/*--------------------------------------HISTORY CLINIC---------------------------------------------*/
CREATE TABLE patients (
    /*ALL THIS INFORMATION BE ENCRYPTED EXCEPT THE <KEY_ID> BECAUSE THE USER CAN USE IT FOR SEARCHING*/
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    
    key_id VARCHAR(100) UNIQUE NOT NULL, -- could be a national ID, social security number, or a custom identifier
    name TEXT NOT NULL,
    phone TEXT,
    cellphone TEXT,
    email TEXT,
    
    birth_date DATE,
    
    address TEXT,
    notes TEXT,
    
    status ENUM('active', 'inactive') DEFAULT 'active',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (clinic_id) REFERENCES clinic(id) ON DELETE CASCADE
);

/*--------------------------------------SALES---------------------------------------------*/
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clinic_id INT NOT NULL,
    
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration_minutes INT, -- optional for appointments
    
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (clinic_id) REFERENCES clinic(id) ON DELETE CASCADE
);

CREATE TABLE sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    user_id INT NOT NULL,
    clinic_id INT NOT NULL,
    patient_id INT,
    
    sale_date DATE NOT NULL,
    
    subtotal DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    
    payment_method ENUM('cash', 'card', 'transfer') DEFAULT 'cash',

    status ENUM('completed', 'cancelled') DEFAULT 'completed',
    
    notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (clinic_id) REFERENCES clinic(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL
);

CREATE TABLE sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    sale_id INT NOT NULL,
    service_id INT NOT NULL,
    
    quantity INT DEFAULT 1,
    
    price DECIMAL(10,2) NOT NULL,
    discount DECIMAL(10,2) DEFAULT 0,
    
    total DECIMAL(10,2) NOT NULL,
    
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
);



CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,

    amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'transfer') DEFAULT 'cash',
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    received_by VARCHAR(150), -- nombre del encargado
    
    signature JSON, -- coordenadas del canvas
    status ENUM('paid', 'pending', 'failed') DEFAULT 'paid',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
);



/*--------------------------------------APPOINTMENTS---------------------------------------------*/
CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    patient_id INT NOT NULL,
    clinic_id INT NOT NULL,
    service_id INT NOT NULL,

    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    
    reason VARCHAR(255),
    notes TEXT,
    link VARCHAR(255), -- para telemedicina
    
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
    
    reminder_sent BOOLEAN DEFAULT FALSE,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,



    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (clinic_id) REFERENCES clinic(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT
);


/*--------------------------------------CLINIC---------------------------------------------*/
CREATE TABLE prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    user_id INT NOT NULL, -- doctor
    clinic_id INT NOT NULL,
    patient_id INT NOT NULL,
    
    appointment_id INT, -- opcional (si viene de una cita)
    sale_id INT, -- opcional (si viene de una venta)
    
    diagnosis TEXT,
    general_instructions TEXT,
    
    issued_date DATE NOT NULL,
    
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (clinic_id) REFERENCES clinic(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE SET NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE SET NULL
);

CREATE TABLE prescription_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    prescription_id INT NOT NULL,
    
    medicine_name VARCHAR(150) NOT NULL,
    
    dosage VARCHAR(100), -- ej: "500mg"
    frequency VARCHAR(100), -- ej: "cada 8 horas"
    duration VARCHAR(100), -- ej: "7 días"
    
    instructions TEXT, -- ej: "después de alimentos"
    
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
);



/*--------------------------------------MEDICAL RECORDS---------------------------------------------*/
CREATE TABLE medical_records (
    id INT AUTO_INCREMENT PRIMARY KEY,

    patient_id INT NOT NULL,
    clinic_id INT NOT NULL,
    /*----------------------COMPANY DATA--------------------*/
    company_date DATE,
    company_name VARCHAR(255),
    company_center_type INT,

    /* -------------------- DOMICILIO -------------------- */
    street_address VARCHAR(255),
    ext_number VARCHAR(20),
    int_number VARCHAR(20),
    neighborhood VARCHAR(100),
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(10),

    /* -------------------- DATOS PERSONALES -------------------- */
    marital_status ENUM('soltero','casado','separado','divorciado','union_libre','viudo'),
    education_level ENUM('primaria','secundaria','bachillerato','tecnica','licenciatura','posgrado'),

    /* -------------------- DATOS MÉDICOS (IMPORTANTE PARA ESCALAR) -------------------- */
    weight DECIMAL(5,2),
    height DECIMAL(5,2),
    blood_pressure VARCHAR(20),
    allergies TEXT,
    chronic_diseases TEXT,

    /* -------------------- NOTAS GENERALES -------------------- */
    notes TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (clinic_id) REFERENCES clinic(id) ON DELETE CASCADE



    /*--------------------------family data--------------------------*/
    family_history JSON,

    /* -------------------- GINECO-OBSTÉTRICOS -------------------- */
    menarche_age INT,
    sexual_onset_age INT,
    sexual_partners_count INT,
    contraceptive_method VARCHAR(100),
    last_menstrual_period DATE,
    menstrual_rhythm ENUM('Regular','Irregular'),
    pregnancies_count INT,
    births_count INT,
    c_sections_count INT,
    abortions_count INT,
    living_children_count INT,
    pap_smear_done ENUM('Si','No'),
    pap_smear_result VARCHAR(150),
    ob_gyn_observations TEXT,

    /* -------------------- LABORAL (JSON) -------------------- */
    occupational_history JSON,
    note_laboratory TEXT,

    /* -------------------- ANTECEDENTES PERSONALES -------------------- */
    personal_chronic ENUM('Neg','Pos'),
    personal_chronic_comment VARCHAR(255),

    personal_trauma ENUM('Neg','Pos'),
    personal_trauma_comment VARCHAR(255),

    personal_surgery ENUM('Neg','Pos'),
    personal_surgery_comment VARCHAR(255),

    personal_allergy ENUM('Neg','Pos'),
    personal_allergy_comment VARCHAR(255),

    personal_transfusion ENUM('Neg','Pos'),
    personal_transfusion_comment VARCHAR(255),
    personal_transfusion_date DATE,
    personal_transfusion_type VARCHAR(50),

    /* -------------------- HÁBITOS Y ESTILO DE VIDA -------------------- */
    lifestyle_smoking ENUM('No','Ocasional','Frecuente'),
    lifestyle_alcohol ENUM('No','Ocasional','Frecuente'),
    lifestyle_drugs ENUM('No','Si'),
    lifestyle_drugs_type VARCHAR(150),
    lifestyle_drugs_frequency VARCHAR(100),
    lifestyle_activity ENUM('Nula','Moderada','Alta'),
    lifestyle_diet ENUM('Balanceada','Regular','Deficiente'),

    /* -------------------- CHILDREN   -------------------- */
    children JSON,

    /* -------------------- MEDICAL EXAM   -------------------- */
    -- JSON con todos los checkboxes
    symptoms JSON,

    -- Comentarios
    physical_exam_notes TEXT,

    -- Actividad física
    does_exercise BOOLEAN,
    exercise_type VARCHAR(100),
    exercise_frequency VARCHAR(100),

    -- Capacidad física
    safety_shoe_impediment BOOLEAN,
    dominant_hand ENUM('right', 'left', 'ambidextrous'),
);