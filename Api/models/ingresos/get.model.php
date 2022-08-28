<?php 
require_once 'models/config/connection.php';

class Get_IngresosModel {

    // Data
    static function getIngresos ($params) {

        try {

            $column = $params['column'];
            $value = $params['value'];

            $sql = "SELECT * FROM ingresos WHERE $column = :$column";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":$column", $value, PDO::PARAM_STR);
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
                'step' => 'model: getIngresos',
            );

        }

        return $response;
        
    }

}