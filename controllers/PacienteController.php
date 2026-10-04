<?php

class PacienteController extends Controller
{

    private $pacienteModel;
    private $citaModel;
    private $medicoModel;

    public function __construct($action, $request)
    {
        parent::__construct($action, $request);
        $this->medicoModel = new MedicoModel();
        $this->citaModel = new CitaModel();
        $this->pacienteModel = new PacienteModel();
    }



    public function dashboard()
    {
        $this->requireAuth('paciente');

        $id = $_SESSION['user_id'];
        $paciente = $this->pacienteModel->findById($id);
        $citas = $this->citaModel->getByPaciente($id);

        $this->view('pacientes/dashboard', [
            'paciente'=> $paciente,
            'citas' => $citas,
        ]);
    }

    public function buscarCitas()
    {
        $this->requireAuth('paciente');

        $citas = $this->citaModel->getDisponibles();

        $this->view('pacientes/buscar_citas', [
            'citas' => $citas,
        ]);
    }

    public function detalle($id)
    {
        $this->requireAuth('paciente');

        $cita = $this->citaModel->findById($id);
        if (!$cita || $cita->paciente_id != $_SESSION['user_id']) {
            Messages::set('danger', 'No tienes permiso para ver esta cita.');
            $this->redirect('paciente/dashboard');
        }

        $this->view('pacientes/detalle_cita', ['cita' => $cita]);
    }

    public function perfil()
    {
        $this->requireAuth('paciente');
        $paciente = $this->pacienteModel->findById($_SESSION['user_id']);
        $this->view('pacientes/perfil', ['paciente' => $paciente]);
    }

    public function perfilPost()
    {
        $this->requireAuth('paciente');

        $actual = $_POST['password_actual'] ?? '';
        $nueva = $_POST['password_nueva'] ?? '';
        $nueva2 = $_POST['password_nueva2'] ?? '';

        if (empty($actual) && empty($nueva) && empty($nueva2)) {
            Messages::set('danger', 'No has introducido ningún cambio.');
            $this->redirect('paciente/perfil');
        }

        if (empty($actual) || empty($nueva) || empty($nueva2)) {
            Messages::set('danger', 'Debes rellenar todos los campos de contraseña.');
            $this->redirect('paciente/perfil');
        }

        if (strlen($nueva) < 8) {
            Messages::set('danger', 'La nueva contraseña debe tener al menos 8 caracteres.');
            $this->redirect('paciente/perfil');
        }

        if ($nueva !== $nueva2) {
            Messages::set('danger', 'Las contraseñas nuevas no coinciden.');
            $this->redirect('paciente/perfil');
        }

        $paciente = $this->pacienteModel->findById($_SESSION['user_id']);

        if (md5($actual) != $paciente->password) {
            Messages::set('danger', 'La contraseña actual no es correcta.');
            $this->redirect('paciente/perfil');
        }

        $this->pacienteModel->updatePassword($_SESSION['user_id'], md5($nueva));
        Messages::set('success', 'Contraseña actualizada correctamente.');
        $this->redirect('paciente/perfil');
    }

    public function reservar($id)
    {
        $this->requireAuth('paciente');

        $aseguradora = trim($_POST['aseguradora'] ?? '');

        if (empty($aseguradora)) {
            Messages::set('danger', 'Debes indicar tu aseguradora.');
            $this->redirect('paciente/buscarCitas');
        }

        $ok = $this->citaModel->solicitar($id, $_SESSION['user_id'], $aseguradora);

        if ($ok) {
            Messages::set('success', 'Cita reservada correctamente.');
        } else {
            Messages::set('danger', 'Lo sentimos, esa cita ya no está disponible.');
        }

        $this->redirect('paciente/dashboard');
    }
}
