<?php

// Instance of class
$putPedidosController = new Put_PedidosController();
$configController = new ConfigController();

// Empty Puts
if ( !isset( $_GET['column'] ) || !isset( $_GET['value'] ) ) {
    return $configController->dataError('Not found', 404, 'routes: put pedidos');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: put pedidos');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: put pedidos');
}

$module = $validateRoute['data'];

$data = array();
parse_str(file_get_contents('php://input'), $data);

// Update user
if ( $module === 'updatePedido' ) return $putPedidosController->updatePedido($_GET, $data);

// Error 404
return $configController->dataError('Not found', 404, 'routes: put pedidos');