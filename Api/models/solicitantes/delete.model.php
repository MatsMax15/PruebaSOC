<?php

class Delete_SolicitantesModel {

    // Eliminar Solicitante
    static public function deleteSolicitante ($params) {

        try {

            $column = $params['column'];
            $value = $params['value'];

            // Obtener datos del solicitante
            $data_solicitante = Get_SolicitantesModel::getSolicitante($column, $value);
            $name = $data_solicitante['data']->nombre . ' ' . $data_solicitante['data']->apellido_paterno . ' ' . $data_solicitante['data']->apellido_materno;
            
            $sql = "DELETE FROM solicitantes WHERE $column = :$column";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":$column", $value);
            $stmt->execute();

            // Eliminar pedidos del solicitante
            $sql = "DELETE FROM pedidos WHERE id_solicitante = :id_solicitante";
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(":id_solicitante", $value);
            $stmt->execute();

            $response = array(
                'status' => 200,
                'message' => 'Se eliminó el solicitante ' . $name,
                'data' => ''
            );
            
        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: deleteSolicitantes',
            );

        }

        return $response;

    }

}