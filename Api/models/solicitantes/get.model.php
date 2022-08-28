<?php 
require_once 'models/config/connection.php';

class Get_SolicitantesModel {

    // Data
    static function getSolicitantes () {

        try {

            $sql = "SELECT * FROM solicitantes";
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
                'step' => 'model: getSolicitantes',
            );

        }

        return $response;
        
    }

    // Get Solicitante
    static function getSolicitante ( $column, $value ) {

        try {

            $sql = "SELECT * FROM solicitantes WHERE $column = :$column";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":$column", $value, PDO::PARAM_STR);
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
                'step' => 'model: getSolicitante',
            );

        }

        return $response;
        

    }

}