<?php 

class Post_PedidosController {

    // Create Pedido
    static public function createPedido ($params) {

        $response = Post_PedidoModel::createPedido($params);

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: createPedido');

    }

}