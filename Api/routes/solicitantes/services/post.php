<?php

$configController = new ConfigController();
$postSolicitantesController = new Post_SolicitantesController();

// Empty Posts
if ( !isset( $_POST ) ) {
    return $configController->dataError('Not found', 404, 'routes: post solicitantes');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: post solicitantes');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 208, 'routes: put solicitantes');
}

$module = $validateRoute['data'];

// Crear Solicitante
if ( $module === 'createSolicitante' ) return $postSolicitantesController->createSolicitante($_POST);

// Error 404
return $configController->dataError('Not found', 404, 'routes: post solicitantes');