<?php

$configController = new ConfigController();
$deleteSolicitanteController = new Delete_SolicitantesController();

// Empty Deletes
if ( !isset( $_GET['column'] ) || !isset( $_GET['value'] ) ) {
    return $configController->dataError('Data not found', 404, 'routes: delete solicitantes');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: delete solicitantes');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: delete solicitantes');
}

// Obtener el modulo al que se realiza la petición
$module = $validateRoute['data'];

// Delete user
if ( $module === 'deleteSolicitante' ) return $deleteSolicitanteController->deleteSolicitante($_GET);

// Error 404
return $configController->dataError('Not found', 404, 'routes: delete solicitantes');