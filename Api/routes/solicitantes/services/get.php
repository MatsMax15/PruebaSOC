<?php

$configController = new ConfigController();
$getSolicitantesController = new Get_SolicitantesController();

// Empty Gets
if ( !isset( $_GET ) ) {
    return $configController->dataError('Not found', 404, 'routes: get solicitantes');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: get solicitantes');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: get solicitantes');
}

// Obtener el modulo al que se realiza la petición
$module = $validateRoute['data'];

// Routes solicitantes
if ( $module === 'solicitantes' ) return $getSolicitantesController->getSolicitantes();

if ( $module === 'solicitante' ) return $getSolicitantesController->getSolicitante($_GET['column'], $_GET['value']);

// Error 404
return $configController->dataError('Not found', 404, 'routes: get solicitantes');