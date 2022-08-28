<?php

$configController = new ConfigController();
$getComprobantesController = new Get_ComprobantesController();

// Empty Gets
if ( !isset( $_GET ) ) {
    return $configController->dataError('Not found', 404, 'routes: get comprobantes');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: get comprobantes');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: get comprobantes');
}

// Obtener el modulo al que se realiza la petición
$module = $validateRoute['data'];

// Routes comprobantes
if ( $module === 'comprobantes' ) return $getComprobantesController->getComprobantes();

// Error 404
return $configController->dataError('Not found', 404, 'routes: get comprobantes');