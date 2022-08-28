<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

// Errors
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/php_error_log.txt');

// ::::::::::::: Controllers ::::::::::::: //
require_once 'controllers/routes.controller.php';
require_once 'controllers/config/config.controller.php';

// Solicitantes
require_once 'controllers/solicitantes/get.controller.php';
require_once 'controllers/solicitantes/post.controller.php';
require_once 'controllers/solicitantes/put.controller.php';
require_once 'controllers/solicitantes/delete.controller.php';

// Ingresos
require_once 'controllers/ingresos/get.controller.php';

// Comprobantes
require_once 'controllers/comprobantes/get.controller.php';

// Empleos
require_once 'controllers/empleos/get.controller.php';

// Pedidos
require_once 'controllers/pedidos/get.controller.php';
require_once 'controllers/pedidos/post.controller.php';
require_once 'controllers/pedidos/put.controller.php';
require_once 'controllers/pedidos/delete.controller.php';

// ::::::::::::: Models ::::::::::::: //
// Solicitantes
require_once 'models/solicitantes/get.model.php';
require_once 'models/solicitantes/post.model.php';
require_once 'models/solicitantes/put.model.php';
require_once 'models/solicitantes/delete.model.php';

// Ingresos
require_once 'models/ingresos/get.model.php';

// Comprobantes
require_once 'models/comprobantes/get.model.php';

// Empleos
require_once 'models/empleos/get.model.php';

// Pedidos
require_once 'models/pedidos/get.model.php';
require_once 'models/pedidos/post.model.php';
require_once 'models/pedidos/put.model.php';
require_once 'models/pedidos/delete.model.php';

$route = new Routes_Controller();
$route->index();