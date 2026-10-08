<?php

class AuthController extends Controller
{

    private $medicoModel;
    private $pacienteModel;

    public function __construct($action, $request)
    {
        parent::__construct($action, $request);
        $this->medicoModel = new MedicoModel();
        $this->pacienteModel = new PacienteModel();
    }

    public function login()
    {
        $this->requireGuest();
        $this->view('auth/login');
    }

    public function loginPost()
    {
        $this->requireGuest();
        $this->requirePost('auth/login');

        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $this->validRole($_POST['role'] ?? '');

        if (empty($identifier) || empty($password)) {
            Messages::set('danger', 'Por favor rellena todos los campos.');
            $this->redirect('auth/login');
        }

        if ($role === 'medico') {
            $user = $this->medicoModel->findByEmailOrPhone($identifier);
        } else {
            $user = $this->pacienteModel->findByEmailOrPhone($identifier);
        }

        if (!$user || !Password::verify($password, $user->password)) {
            Messages::set('danger', 'Credenciales incorrectas. Inténtalo de nuevo.');
            $this->redirect('auth/login');
        }

        if (Password::needsUpgrade($user->password)) {
            $newHash = Password::hash($password);
            if ($role === 'medico') {
                $this->medicoModel->updatePassword($user->id, $newHash);
            } else {
                $this->pacienteModel->updatePassword($user->id, $newHash);
            }
        }

        session_regenerate_id(true);
        Csrf::regenerate();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->nombre . ' ' . $user->apellido;
        $_SESSION['user_role'] = $role;

        Messages::set('success', '¡Bienvenido/a, ' . $user->nombre . '!');
        $this->redirect($role . '/dashboard');
        }

    public function register()
    {
        $this->requireGuest();
        $this->view('auth/register', ['role' => 'paciente']);
    }

    public function registerMedico()
    {
        $this->requireGuest();
        $this->view('auth/register', ['role' => 'medico']);
    }

    public function registerPost()
    {
        $this->requireGuest();
        $this->requirePost('auth/register');

        $role = $this->validRole($_POST['role'] ?? '');

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'fecha_nac' => trim($_POST['fecha_nac'] ?? ''),
            'sexo' => $_POST['sexo'] ?? '',
            'password' => $_POST['password'] ?? '',
            'password2' => $_POST['password2'] ?? '',
        ];
        $errors = $this->validateRegister($data, $role);

        if (!empty($errors)) {
            foreach ($errors as $e) {
                Messages::set('danger', $e);
            }
            $this->redirect('auth/register' . ($role === 'medico' ? 'Medico' : ''));
        }
        $data['password'] = Password::hash($data['password']);

        if ($role === 'medico') {
            $created = $this->medicoModel->create($data);
        } else {
            $created = $this->pacienteModel->create($data);
        }

        if ($created) {
            Messages::set('success', 'Registro completado. Ya puedes iniciar sesión.');
            $this->redirect('auth/login');
        } else {
            Messages::set('danger', 'Error al crear la cuenta. Inténtalo de nuevo.');
            $this->redirect('auth/register' . ($role === 'medico' ? 'Medico' : ''));
        }
    }

    public function logout()
    {
        $this->requirePost('');

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();
        $this->redirect('auth/login');
    }

    private function validateRegister($data, $role)
    {
        $errors = [];

        foreach (['nombre', 'apellido', 'telefono', 'email', 'fecha_nac', 'sexo', 'password'] as $field) {
            if (empty($data[$field])) {
                $errors[] = 'Todos los campos son obligatorios.';
                return $errors;
            }
        }

        if (!in_array($data['sexo'], ['M', 'F', 'O'], true)) {
            $errors[] = 'El sexo seleccionado no es válido.';
        }

        $fecha = DateTime::createFromFormat('!Y-m-d', $data['fecha_nac']);
        if (!$fecha || $fecha->format('Y-m-d') !== $data['fecha_nac'] || $fecha > new DateTime('today')) {
            $errors[] = 'La fecha de nacimiento no es válida.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico no tiene un formato válido.';
        }

        if (strlen($data['password']) < 8) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres.';
        }

        if ($data['password'] !== $data['password2']) {
            $errors[] = 'Las contraseñas no coinciden.';
        }

        if ($role === 'medico') {
            if ($this->medicoModel->emailExists($data['email'])) {
                $errors[] = 'Este correo ya está registrado como médico.';
            }
            if ($this->medicoModel->phoneExists($data['telefono'])) {
                $errors[] = 'Este teléfono ya está registrado como médico.';
            }
        } else {
            if ($this->pacienteModel->emailExists($data['email'])) {
                $errors[] = 'Este correo ya está registrado como paciente.';
            }
            if ($this->pacienteModel->phoneExists($data['telefono'])) {
                $errors[] = 'Este teléfono ya está registrado como paciente.';
            }
        }

        return $errors;
    }

    // Cualquier valor que no sea 'medico' se trata como paciente.
    private function validRole($role)
    {
        return $role === 'medico' ? 'medico' : 'paciente';
    }
}
