<?php

$configController = new ConfigController();
$postPedidosController = new Post_PedidosController();

// Empty Posts
if ( !isset( $_POST ) ) {
    return $configController->dataError('Not found', 404, 'routes: post pedidos');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: post pedidos');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 208, 'routes: put pedidos');
}

$module = $validateRoute['data'];

// Crear Pedido
if ( $module === 'createPedido' ) return $postPedidosController->createPedido($_POST);

// Error 404
return $configController->dataError('Not found', 404, 'routes: post pedidos');