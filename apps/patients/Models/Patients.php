<?php
namespace App\Models;

class Patients extends Model {
    

    public function all() {
        $stmt = $this->db->query("SELECT * FROM pacientes ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Guardar un nuevo paciente (usando Sentencias Preparadas para evitar Inyección SQL)
    public function save($data) {
        $sql = "INSERT INTO pacientes (nombre, apellido, email, telefono) 
                VALUES (:nombre, :apellido, :email, :telefono)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nombre'   => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':email'    => $data['email'],
            ':telefono' => $data['telefono']
        ]);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM pacientes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}