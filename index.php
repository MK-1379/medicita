<?php
session_start();
require('config.php');

require('classes/Messages.php');
require('classes/Csrf.php');
require('classes/Bootstrap.php');
require('classes/Controller.php');
require('classes/Model.php');
require('classes/Password.php');

require('controllers/AuthController.php');
require('controllers/HomeController.php');
require('controllers/MedicoController.php');
require('controllers/PacienteController.php');

require('models/MedicoModel.php');
require('models/CitaModel.php');
require('models/PacienteModel.php');

$app = new Bootstrap();
$app->run();