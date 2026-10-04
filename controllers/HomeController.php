<?php

class HomeController extends Controller
{

    private $medicoModel;
    private $citaModel;

    public function __construct($action, $request)
    {
        parent::__construct($action, $request);
        $this->medicoModel = new MedicoModel();
        $this->citaModel = new CitaModel();
    }

    public function index()
    {

        $medicos = $this->medicoModel->getAll();
        $totalCitas = count($this->citaModel->getDisponibles());

        $this->view('home/index', [
            'medicos' => $medicos,
            'totalCitas' => $totalCitas
        ]);
    }

    public function medicos()
    {

        $filtro = $_GET['especialidad'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 4;
        $offset = ($page - 1) * $perPage;

        if ($filtro != '') {
            $medicos = $this->medicoModel->filterByEspecialidad($filtro, $perPage, $offset);
            $total = $this->medicoModel->countByEspecialidad($filtro);
        } else {
            $medicos = $this->medicoModel->getAllPaginated($perPage, $offset);
            $total = $this->medicoModel->countAll();
        }

        $totalPages = (int) ceil($total / $perPage);

        $this->view('home/medicos', [
            'medicos' => $medicos,
            'filtro' => $filtro,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }
}
