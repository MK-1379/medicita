<?php

class MedicoController extends Controller
{

    private $medicoModel;
    private $citaModel;
    private $perPage = 8;

    public function __construct($action, $request)
    {
        parent::__construct($action, $request);
        $this->medicoModel = new MedicoModel();
        $this->citaModel = new CitaModel();
    }

    public function dashboard()
    {
        $this->requireAuth('medico');

        $medicoId = $_SESSION['user_id'];
        $medico = $this->medicoModel->findById($medicoId);

        $page = max(1, (int)($_GET['page'] ?? 1));
        $offset = ($page - 1) * $this->perPage;

        $citas = $this->citaModel->getByMedico($medicoId, $this->perPage, $offset);
        $totalCitas = $this->citaModel->countByMedico($medicoId);
        $totalPages = (int) ceil($totalCitas / $this->perPage);

        $disponibles = $this->citaModel->countDisponibles($medicoId);
        $asignadas = $this->citaModel->countAsignadas($medicoId);

        $this->view('medicos/dashboard', [
            'medico' => $medico,
            'citas' => $citas,
            'page' => $page,
            'totalPages' => $totalPages,
            'disponibles' => $disponibles,
            'asignadas' => $asignadas,
        ]);
    }

    public function perfil()
    {
        $this->requireAuth('medico');
        $medico = $this->medicoModel->findById($_SESSION['user_id']);
        $this->view('medicos/perfil', ['medico' => $medico]);
    }

    public function perfilPost()
    {
        $this->requireAuth('medico');
        $this->requirePost('medico/perfil');

        $especialidad = trim($_POST['especialidad'] ?? '');
        $horario = trim($_POST['horario'] ?? '');

        if (empty($especialidad)) {
            Messages::set('danger', 'La especialidad no puede estar vacía.');
            $this->redirect('medico/perfil');
        }

        $this->medicoModel->updateProfile($_SESSION['user_id'], $especialidad, $horario);
        Messages::set('success', 'Perfil actualizado correctamente.');
        $this->redirect('medico/perfil');
    }

    public function crearCita()
    {
        $this->requireAuth('medico');
        $this->view('medicos/crear_cita');
    }

    public function crearCitaPost()
    {
        $this->requireAuth('medico');
        $this->requirePost('medico/crearCita');

        $fecha = $_POST['fecha'] ?? '';
        $hora  = $_POST['hora'] ?? '';
        $lugar = trim($_POST['lugar'] ?? '');

        $error = $this->validateCita($fecha, $hora, $lugar);
        if ($error) {
            Messages::set('danger', $error);
            $this->redirect('medico/crearCita');
        }

        $created = $this->citaModel->create([
            'medico_id' => $_SESSION['user_id'],
            'fecha' => $fecha,
            'hora' => $hora,
            'lugar' => $lugar,
        ]);

        if ($created) {
            Messages::set('success', 'Cita creada correctamente.');
        } else {
            Messages::set('danger', 'Error al crear la cita.');
        }

        $this->redirect('medico/dashboard');
    }

    public function editarCitaPost($id)
    {
        $this->requireAuth('medico');
        $this->requirePost('medico/detalleCita/' . $id);

        $fecha = $_POST['fecha'] ?? '';
        $hora  = $_POST['hora'] ?? '';
        $lugar = trim($_POST['lugar'] ?? '');

        $error = $this->validateCita($fecha, $hora, $lugar);
        if ($error) {
            Messages::set('danger', $error);
            $this->redirect('medico/detalleCita/' . $id);
        }

        $updated = $this->citaModel->update($id, $_SESSION['user_id'], [
            'fecha' => $fecha,
            'hora' => $hora,
            'lugar' => $lugar,
        ]);

        if ($updated) {
            Messages::set('success', 'Cita actualizada correctamente.');
        } else {
            Messages::set('danger', 'No se realizaron cambios.');
        }

        $this->redirect('medico/dashboard');
    }

    public function eliminarCita($id)
    {
        $this->requireAuth('medico');
        $this->requirePost('medico/dashboard');

        $deleted = $this->citaModel->delete($id, $_SESSION['user_id']);

        if ($deleted) {
            Messages::set('success', 'Cita eliminada correctamente.');
        } else {
            Messages::set('danger', 'No se puede eliminar: la cita está asignada o no te pertenece.');
        }

        $this->redirect('medico/dashboard');
    }

    public function detalleCita($id)
    {
        $this->requireAuth('medico');

        $cita = $this->citaModel->findById($id);

        if (!$cita || $cita->medico_id != $_SESSION['user_id']) {
            Messages::set('danger', 'Cita no encontrada.');
            $this->redirect('medico/dashboard');
        }

        $this->view('medicos/detalle_cita', ['cita' => $cita]);
    }

    // Devuelve el mensaje de error, o null si los datos son correctos.
    private function validateCita($fecha, $hora, $lugar)
    {
        if ($fecha === '' || $hora === '' || $lugar === '') {
            return 'Todos los campos son obligatorios.';
        }

        $fechaHora = DateTime::createFromFormat('!Y-m-d H:i', $fecha . ' ' . substr($hora, 0, 5));
        if (!$fechaHora || $fechaHora->format('Y-m-d H:i') !== $fecha . ' ' . substr($hora, 0, 5)) {
            return 'La fecha o la hora no tienen un formato válido.';
        }

        if ($fechaHora < new DateTime()) {
            return 'La cita no puede ser en una fecha u hora que ya ha pasado.';
        }

        if (mb_strlen($lugar) > 150) {
            return 'El lugar no puede superar los 150 caracteres.';
        }

        return null;
    }
}
