<?php

class Put_PedidosController {

    // Update Solicitante
    static public function updatePedido ($params, $data) {

        $response = Put_PedidosModel::updatePedido($params, $data);

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: updatePedido');

    }

}