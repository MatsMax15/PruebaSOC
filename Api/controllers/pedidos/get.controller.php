<?php

class Get_PedidosController {

    // Obtener pedidos
    static public function getPedidos () {

        $response = Get_PedidosModel::getPedidos();

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: getPedidos');

    }

    // Obtener Pedido
    static public function getPedido ($column, $value) {

        $response = Get_PedidosModel::getPedido($column, $value);

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: getPedido');

    }

}