<?php

$configController = new ConfigController();
$getIngresosController = new Get_IngresosController();

// Empty Gets
if ( !isset( $_GET ) ) {
    return $configController->dataError('Not found', 404, 'routes: get ingresos');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: get ingresos');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: get ingresos');
}

// Obtener el modulo al que se realiza la petición
$module = $validateRoute['data'];

// Routes ingresos
if ( $module === 'ingresos' ) return $getIngresosController->getIngresos($_GET);

// Error 404
return $configController->dataError('Not found', 404, 'routes: get ingresos');