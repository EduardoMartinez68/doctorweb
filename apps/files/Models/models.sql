CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    
    phone VARCHAR(20),
    
    role ENUM('doctor', 'admin') DEFAULT 'doctor',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);