<?php

class MedicoModel extends Model
{

    public function findByEmailOrPhone($value)
    {
        $this->query("SELECT * FROM medicos WHERE email = :v OR telefono = :v");
        $this->bind(':v', $value);
        return $this->single();
    }

    public function findById($id)
    {
        $this->query("SELECT * FROM medicos WHERE id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function emailExists($email)
    {
        $this->query("SELECT id FROM medicos WHERE email = :email");
        $this->bind(':email', $email);
        return $this->single() !== false;
    }

    public function phoneExists($telefono)
    {
        $this->query("SELECT id FROM medicos WHERE telefono = :tel");
        $this->bind(':tel', $telefono);
        return $this->single() !== false;
    }

    public function getAll()
    {
        $this->query("SELECT id, nombre, apellido, especialidad, horario
             FROM medicos
             ORDER BY apellido");
        return $this->resultSet();
    }

    public function filterByEspecialidad($esp, $limit = 100, $offset = 0)
    {
        $this->query("SELECT id, nombre, apellido, especialidad, horario
            FROM medicos
            WHERE especialidad LIKE :esp
            ORDER BY apellido
            LIMIT :limit OFFSET :offset");
        $this->bind(':esp', '%' . $esp . '%');
        $this->bind(':limit', (int)$limit);
        $this->bind(':offset', (int)$offset);
        return $this->resultSet();
    }

    public function updateProfile($id, $especialidad, $horario)
    {
        $this->query("UPDATE medicos
             SET especialidad = :esp, horario = :horario
             WHERE id = :id");
        $this->bind(':esp', $especialidad);
        $this->bind(':horario', $horario);
        $this->bind(':id', $id);
        $this->execute();
        return $this->rowCount() > 0;
    }

    public function updatePassword($id, $hashedPassword)
    {
        $this->query("UPDATE medicos SET password = :password WHERE id = :id");
        $this->bind(':password', $hashedPassword);
        $this->bind(':id', $id);
        $this->execute();
    }

    public function create($data)
    {
        $this->query("INSERT INTO medicos (nombre, apellido, telefono, email, fecha_nac, sexo, password)
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

    public function getAllPaginated($limit, $offset)
    {
        $this->query("SELECT id, nombre, apellido, especialidad, horario
            FROM medicos
            ORDER BY apellido
            LIMIT :limit OFFSET :offset");
        $this->bind(':limit', (int)$limit);
        $this->bind(':offset', (int)$offset);
        return $this->resultSet();
    }

    public function countAll()
    {
        $this->query("SELECT COUNT(*) AS total FROM medicos");
        return $this->single()->total;
    }

    public function countByEspecialidad($esp)
    {
        $this->query("SELECT COUNT(*) AS total FROM medicos WHERE especialidad LIKE :esp");
        $this->bind(':esp', '%' . $esp . '%');
        return $this->single()->total;
    }
}
