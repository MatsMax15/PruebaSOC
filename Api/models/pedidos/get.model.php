<?php 
require_once 'models/config/connection.php';

class Get_PedidosModel {

    // Data
    static function getPedidos () {

        try {

            $sql = "SELECT * FROM pedidos";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = array(
                'total' => count($data),
                'results' => $data
            );
    
            $response = array(
                'status' => 200,
                'message' => 'OK',
                'data' => $result
            );

        } catch ( Exception $e ) {
            
            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: getPedidos',
            );

        }

        return $response;
        
    }

    static function getPedido ($column, $value) {

        try {

            $sql = "SELECT * FROM pedidos WHERE $column = :$column";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":$column", $value);
            $stmt->execute();
            $data = $stmt->fetchObject();
    
            $response = array(
                'status' => 200,
                'message' => 'OK',
                'data' => $data
            );

        } catch ( Exception $e ) {
            
            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: getPedido',
            );

        }

        return $response;
        
    }

    // Get Pedido
    static function getPedidosSolicitante ( $column, $value ) {

        try {

            $sql = "SELECT * FROM pedidos WHERE $column = :$column";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":$column", $value, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll();

            $response = array(
                'status' => 200,
                'message' => 'OK',
                'data' => $data
            );

        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: getPedidosSolicitante',
            );

        }

        return $response;
        

    }

}