<?php

class Delete_PedidosController {

    // Update user
    static public function deletePedido ($params) {

        $response = Delete_PedidosModel::deletePedido($params);

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: deletePedido');

    }

}