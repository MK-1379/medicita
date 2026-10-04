<?php

class CitaModel extends Model
{

    public function getDisponibles()
    {
        $this->query("SELECT c.*, m.nombre AS med_nombre, m.apellido AS med_apellido, m.especialidad
             FROM citas c
             JOIN medicos m ON c.medico_id = m.id
             WHERE c.estado = 'disponible'
             ORDER BY c.fecha, c.hora");
        return $this->resultSet();
    }

    public function findById($id)
    {
        $this->query("SELECT c.*,
                    m.nombre AS med_nombre, m.apellido AS med_apellido, m.especialidad,
                    p.nombre AS pac_nombre, p.apellido AS pac_apellido, p.telefono AS pac_telefono
            FROM citas c
            JOIN medicos m ON c.medico_id = m.id
            LEFT JOIN pacientes p ON c.paciente_id = p.id
            WHERE c.id = :id");
        $this->bind(':id', $id);
        return $this->single();
    }

    public function getByMedico($medicoId, $limit, $offset)
    {
        $this->query("SELECT c.*, p.nombre AS pac_nombre, p.apellido AS pac_apellido
             FROM citas c
             LEFT JOIN pacientes p ON c.paciente_id = p.id
             WHERE c.medico_id = :mid
             ORDER BY c.fecha DESC, c.hora DESC
             LIMIT :limit OFFSET :offset");
        $this->bind(':mid', $medicoId);
        $this->bind(':limit', (int)$limit);
        $this->bind(':offset', (int)$offset);
        return $this->resultSet();
    }

    public function countByMedico($medicoId)
    {
        $this->query("SELECT COUNT(*) AS total FROM citas WHERE medico_id = :mid");
        $this->bind(':mid', $medicoId);
        return $this->single()->total;
    }

    public function countDisponibles($medicoId)
    {
        $this->query("SELECT COUNT(*) AS total
             FROM citas
             WHERE medico_id = :mid AND estado = 'disponible'");
        $this->bind(':mid', $medicoId);
        return $this->single()->total;
    }

    public function countAsignadas($medicoId)
    {
        $this->query("SELECT COUNT(*) AS total
             FROM citas
             WHERE medico_id = :mid AND estado = 'asignada'");
        $this->bind(':mid', $medicoId);
        return $this->single()->total;
    }

    public function create($data)
    {
        $this->query("INSERT INTO citas (medico_id, fecha, hora, lugar, estado)
             VALUES (:medico_id, :fecha, :hora, :lugar, 'disponible')");
        $this->bind(':medico_id', $data['medico_id']);
        $this->bind(':fecha', $data['fecha']);
        $this->bind(':hora', $data['hora']);
        $this->bind(':lugar', $data['lugar']);
        $this->execute();
        return true;
    }

    public function update($id, $medicoId, $data)
    {
        $this->query("UPDATE citas
             SET fecha = :fecha, hora = :hora, lugar = :lugar
             WHERE id = :id AND medico_id = :mid");
        $this->bind(':fecha', $data['fecha']);
        $this->bind(':hora', $data['hora']);
        $this->bind(':lugar', $data['lugar']);
        $this->bind(':id', $id);
        $this->bind(':mid', $medicoId);
        $this->execute();
        return $this->rowCount() > 0;
    }

    public function delete($id, $medicoId)
    {
        $this->query("DELETE FROM citas
             WHERE id = :id
               AND medico_id = :mid
               AND estado = 'disponible'");
        $this->bind(':id', $id);
        $this->bind(':mid', $medicoId);
        $this->execute();
        return $this->rowCount() > 0;
    }

    public function getByPaciente($pacienteId)
    {
        $this->query("SELECT c.*, m.nombre AS med_nombre, m.apellido AS med_apellido, m.especialidad
            FROM citas c
            JOIN medicos m ON c.medico_id = m.id
            WHERE c.paciente_id = :pid
            ORDER BY c.fecha DESC, c.hora DESC");
        $this->bind(':pid', $pacienteId);
        return $this->resultSet();
    }

    public function solicitar($citaId, $pacienteId, $aseguradora)
    {
        $this->query("UPDATE citas
             SET paciente_id = :pid,
                 aseguradora = :aseg,
                 estado = 'asignada'
             WHERE id = :id AND estado = 'disponible'");
        $this->bind(':pid', $pacienteId);
        $this->bind(':aseg', $aseguradora);
        $this->bind(':id', $citaId);
        $this->execute();
        return $this->rowCount() > 0;
    }
}
