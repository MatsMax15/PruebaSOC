<?php 
require_once 'models/config/connection.php';

class Get_EmpleosModel {

    // Data
    static function getEmpleos () {

        try {

            $sql = "SELECT * FROM tipo_trabajos";
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
                'step' => 'model: getEmpleos',
            );

        }

        return $response;
        
    }

}