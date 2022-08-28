<?php 

$route = explode('/', $_SERVER['REQUEST_URI'])[4];
$route = explode('?', $route)[0];

// Empty route
if ( $route === '' )  {
    echo json_encode(array(
        'status' => 200,
        'message' => 'Welcome to the API',
    ));

    return;
}

// Solicitantes
if( $route === 'solicitantes' ) return include 'solicitantes/solicitantes.routes.php';

// Ingresos
if( $route === 'ingresos' ) return include 'ingresos/ingresos.routes.php';

// Comprobantes
if( $route === 'comprobantes' ) return include 'comprobantes/comprobantes.routes.php';

// Empleos
if( $route === 'empleos' ) return include 'empleos/empleos.routes.php';

// Pedidos
if( $route === 'pedidos' ) return include 'pedidos/pedidos.routes.php';