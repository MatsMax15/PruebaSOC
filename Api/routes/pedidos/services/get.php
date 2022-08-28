<?php

$configController = new ConfigController();
$getPedidosController = new Get_PedidosController();

// Empty Gets
if ( !isset( $_GET ) ) {
    return $configController->dataError('Not found', 404, 'routes: get pedidos');
}

// Validar que la ruta exista
$validateRoute = $configController->validateRoute('routes: get pedidos');
if ( $validateRoute['status'] !== 200 ) {
    return $configController->dataError('Not found', 404, 'routes: get pedidos');
}

// Obtener el modulo al que se realiza la petición
$module = $validateRoute['data'];

// Routes pedidos
if ( $module === 'pedidos' ) return $getPedidosController->getPedidos();

if ( $module === 'pedido' ) return $getPedidosController->getPedido($_GET['column'], $_GET['value']);

// Error 404
return $configController->dataError('Not found', 404, 'routes: get pedidos');