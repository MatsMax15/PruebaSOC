<?php

$configController = new ConfigController();
$deletePedidosController = new Delete_PedidosController();

// Empty Deletes
if ( !isset( $_GET['column'] ) || !isset( $_GET['value'] ) ) {
    return $configController->dataError('Data not found', 404, 'routes: delete pedidos');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: delete pedidos');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: delete pedidos');
}

// Obtener el modulo al que se realiza la petición
$module = $validateRoute['data'];

// Delete user
if ( $module === 'deletePedido' ) return $deletePedidosController->deletePedido($_GET);

// Error 404
return $configController->dataError('Not found', 404, 'routes: delete pedidos');