<?php 
require_once 'models/config/connection.php';

class Post_IngresosModel {

    // Registrar Ingresos
    static public function createIngresos ($params, $id_solicitante) {

        try {
            $fecha_registro = date('Y-m-d H:i:s');

            $ingresos = [];
            $id_ingresos = [];
            foreach ( $params as $key ) {

                if ( isset($key->id) ) {
                    $id = $key->id === '' ? '' : $key->id;
                }
                else {
                    $id = '';
                }

                $empresa = $key->empresa;
                $tipo_comprobante = $key->tipo_comprobante;
                $salario_bruto = $key->salario_bruto;
                $salario_neto = $key->salario_neto;
                $tipo_empleo = $key->tipo_empleo;
                $fecha_inicio = $key->fecha_inicio;

                $action = ( $id ) ? 'UPDATE' : 'INSERT INTO';
                $sql_fecha_registro = ( !$id ) ? ', fecha_registro = :fecha_registro' : '';

                $sql_where = ( $id ) ? 'WHERE id = :id' : '';

                $sql = "$action ingresos SET    id_solicitante = :id_solicitante, 
                                                empresa = :empresa, 
                                                tipo_comprobante = :tipo_comprobante, 
                                                salario_bruto = :salario_bruto, 
                                                salario_neto = :salario_neto, 
                                                tipo_empleo = :tipo_empleo, 
                                                fecha_inicio = :fecha_inicio
                                                $sql_fecha_registro
                                                $sql_where";

                $stmt = Connection::connect()->prepare($sql);

                if ( $id )
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                $stmt->bindParam(':id_solicitante', $id_solicitante, PDO::PARAM_INT);
                $stmt->bindParam(':empresa', $empresa, PDO::PARAM_STR);
                $stmt->bindParam(':tipo_comprobante', $tipo_comprobante, PDO::PARAM_STR);
                $stmt->bindParam(':salario_bruto', $salario_bruto, PDO::PARAM_STR);
                $stmt->bindParam(':salario_neto', $salario_neto, PDO::PARAM_STR);
                $stmt->bindParam(':tipo_empleo', $tipo_empleo, PDO::PARAM_STR);
                $stmt->bindParam(':fecha_inicio', $fecha_inicio, PDO::PARAM_STR);

                if ( !$id )
                    $stmt->bindParam(':fecha_registro', $fecha_registro, PDO::PARAM_STR);

                $stmt->execute();

                // Get last inserted id
                $sql = "SELECT MAX(id) AS id FROM ingresos";
                $stmt = Connection::connect()->prepare($sql);
                $stmt->execute();
                $last_id = $stmt->fetchColumn();

                $ingresos[] = $empresa;
                $id_ingresos[] = $id ? $id : $last_id;
            }

            // Eliminar ingresos
            if ( count($id_ingresos) > 0 ) {

                $sql_id_ingresos = implode(',', $id_ingresos);
                $sql = "DELETE FROM ingresos WHERE id NOT IN ($sql_id_ingresos) AND id_solicitante = :id_solicitante";
                $stmt = Connection::connect()->prepare($sql);
                $stmt->bindParam(':id_solicitante', $id_solicitante, PDO::PARAM_INT);
                $stmt->execute();

            }

            $response = array(
                'status' => 200,
                'message' => 'Ingresos registrados correctaemnte',
                'data' => $id_ingresos,
            );

        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: createIngresos',
            );
            
        }

        return $response;

    }

}