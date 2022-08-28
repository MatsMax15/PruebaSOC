<?php
require_once 'models/config/connection.php';
require_once 'models/pedidos/get.model.php';

class Delete_PedidosModel {

    // Eliminar Pedido
    static public function deletePedido ($params) {

        try {

            $column = $params['column'];
            $value = $params['value'];

            // Obtener datos del pedido
            $data_pedido = Get_PedidosModel::getPedido($column, $value);

            $sql = "DELETE FROM pedidos WHERE $column = :$column";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":$column", $value);
            $stmt->execute();

            $response = array(
                'status' => 200,
                'message' => 'Se eliminó el pedido ' . $data_pedido['data']->folio,
                'data' => $data_pedido
            );
            
        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: deletePedido',
            );

        }

        return $response;

    }

}