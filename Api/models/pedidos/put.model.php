<?php

require_once 'models/config/connection.php';
require_once 'models/solicitantes/get.model.php';
require_once 'models/ingresos/post.model.php';

class Put_PedidosModel {

    // Update user
    static public function updatePedido ($params, $data) {

        try {

            $value = $params['value'];
            $column = $params['column'];

            // Obtener datos del pedido
            $getPedido = Get_PedidosModel::getPedido($column, $value);
            $getPedido = $getPedido['data'];
            $id_solicitante = $getPedido->id_solicitante;
            $folio = $getPedido->folio;

            // Obtener pedidos de solicitante
            $data_pedidos = Get_PedidosModel::getPedidosSolicitante('id_solicitante', $id_solicitante);
            $data_pedidos = $data_pedidos['data'];

            // Validar que el destino no exista
            $destino_existe = false;
            foreach ( $data_pedidos as $pedido ) {
                if ( $pedido['destino'] === $data['destino'] && $pedido['id'] != $value ) {
                    $destino_existe = true;
                }
            }

            if ( $destino_existe ) {

                $response = array(
                    'status' => 204,
                    'message' => 'El solicitante ya cuenta con un pedido para el destino ' . $data['destino'],
                    'step' => 'model: createPedido',
                );

                return $response;

            }

            $sql = "UPDATE pedidos SET  fecha = :fecha,
                                        destino = :destino,
                                        monto = :monto,
                                        plazo = :plazo
                                    WHERE 
                                        id = :id";

            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(':id', $value, PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $data['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':destino', $data['destino'], PDO::PARAM_STR);
            $stmt->bindParam(':monto', $data['monto'], PDO::PARAM_STR);
            $stmt->bindParam(':plazo', $data['plazo'], PDO::PARAM_STR);
            $stmt->execute();

            $response = array(
                'status' => 200,
                'message' => 'El pedido ' . $folio . ' se ha actualizado correctamente',
                'data' => $data
            );
            
        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: updateSolicitante',
            );

        }

        return $response;

    }

}