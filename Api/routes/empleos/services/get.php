<?php

$configController = new ConfigController();
$getEmpleosController = new Get_EmpleosController();

// Empty Gets
if ( !isset( $_GET ) ) {
    return $configController->dataError('Not found', 404, 'routes: get empleos');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: get empleos');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: get empleos');
}

// Obtener el modulo al que se realiza la petición
$module = $validateRoute['data'];

// Routes empleos
if ( $module === 'empleos' ) return $getEmpleosController->getEmpleos();

// Error 404
return $configController->dataError('Not found', 404, 'routes: get empleos');