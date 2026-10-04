<?php

class PacienteModel extends Model
{

    public function findByEmailOrPhone($value)
    {
        $this->query("SELECT * FROM pacientes WHERE email = :v OR telefono = :v");
        $this->bind(':v', $value);
        return $this->single();
    }

    public function findById($id)
    {
        $this->query("SELECT * FROM pacientes WHERE id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function emailExists($email)
    {
        $this->query("SELECT id FROM pacientes WHERE email = :email");
        $this->bind(':email', $email);
        return $this->single() !== false;
    }

    public function phoneExists($telefono)
    {
        $this->query("SELECT id FROM pacientes WHERE telefono = :tel");
        $this->bind(':tel', $telefono);
        return $this->single() !== false;
    }

    public function getPaginated($limit, $offset)
    {
        $this->query("SELECT id, nombre, apellido, email, telefono
             FROM pacientes
             ORDER BY apellido
             LIMIT :limit OFFSET :offset");
        $this->bind(':limit', $limit);
        $this->bind(':offset', $offset);
        return $this->resultSet();
    }

    public function count()
    {
        $this->query("SELECT COUNT(*) AS total FROM pacientes");
        $row = $this->single();
        return $row->total;
    }

    public function updatePassword($id, $hashedPassword)
    {
        $this->query("UPDATE pacientes SET password = :password WHERE id = :id");
        $this->bind(':password', $hashedPassword);
        $this->bind(':id', $id);
        $this->execute();
    }

    public function create($data)
    {
        $this->query("INSERT INTO pacientes (nombre, apellido, telefono, email, fecha_nac, sexo, password)
             VALUES (:nombre, :apellido, :telefono, :email, :fecha_nac, :sexo, :password)");
        $this->bind(':nombre', $data['nombre']);
        $this->bind(':apellido', $data['apellido']);
        $this->bind(':telefono', $data['telefono']);
        $this->bind(':email', $data['email']);
        $this->bind(':fecha_nac', $data['fecha_nac']);
        $this->bind(':sexo', $data['sexo']);
        $this->bind(':password', $data['password']);
        $this->execute();
        return true;
    }
}
