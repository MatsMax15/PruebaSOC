<?php

// Instance of class
$putSolicitantesController = new Put_SolicitantesController();
$configController = new ConfigController();

// Empty Puts
if ( !isset( $_GET['column'] ) || !isset( $_GET['value'] ) ) {
    return $configController->dataError('Not found', 404, 'routes: put solicitantes');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: put solicitantes');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: put solicitantes');
}

$module = $validateRoute['data'];

$data = array();
parse_str(file_get_contents('php://input'), $data);

// Update user
if ( $module === 'updateSolicitante' ) return $putSolicitantesController->updateSolicitante($_GET, $data);

// Error 404
return $configController->dataError('Not found', 404, 'routes: put solicitantes');