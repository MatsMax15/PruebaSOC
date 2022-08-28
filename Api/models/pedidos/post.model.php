<?php

require_once 'models/config/connection.php';

class Post_PedidoModel {

    // Crear Pedido
    static public function createPedido ($params) {

        try {

            // Obtener pedidos de solicitante
            $data_pedidos = Get_PedidosModel::getPedidosSolicitante('id_solicitante', $params['id_solicitante']);
            $data_pedidos = $data_pedidos['data'];

            // Validar que el destino no exista
            $destino_existe = false;
            foreach ( $data_pedidos as $pedido ) {
                if ( $pedido['destino'] === $params['destino'] ) {
                    $destino_existe = true;
                }
            }

            if ( $destino_existe ) {

                $response = array(
                    'status' => 204,
                    'message' => 'El solicitante ya cuenta con un pedido para el destino ' . $params['destino'],
                    'step' => 'model: createPedido',
                );

                return $response;

            }

            $sql = 'SELECT folio FROM pedidos ORDER BY folio DESC LIMIT 1';
            $stmt = Connection::connect()->prepare($sql);
            $stmt->execute();
            $lastFolio = $stmt->fetchColumn();

            $folio = $lastFolio ? intval($lastFolio) + 1 : 1;
            $folio = str_pad($folio, 6, "0", STR_PAD_LEFT);

            $sql = 'INSERT INTO pedidos SET folio = :folio, 
                                            id_solicitante = :id_solicitante, 
                                            fecha = :fecha,
                                            destino = :destino,
                                            monto = :monto,
                                            plazo = :plazo';

            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(':folio', $folio, PDO::PARAM_STR);
            $stmt->bindParam(':id_solicitante', $params['id_solicitante'], PDO::PARAM_INT);
            $stmt->bindParam(':fecha', $params['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':destino', $params['destino'], PDO::PARAM_STR);
            $stmt->bindParam(':monto', $params['monto'], PDO::PARAM_STR);
            $stmt->bindParam(':plazo', $params['plazo'], PDO::PARAM_STR);
            $stmt->execute();

            $response = array(
                'status' => 200,
                'message' => 'El pedido ' . $folio . ' ha sido creado correctamente',
                'data' => $folio,
            );

        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: createPedido',
            );
            
        }

        return $response;

    }

}