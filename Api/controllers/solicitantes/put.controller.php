<?php

class Put_SolicitantesController {

    // Update Solicitante
    static public function updateSolicitante ($params, $data) {

        $response = Put_SolicitantesModel::updateSolicitante($params, $data);

        echo json_encode($response);
        return;

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: updateSolicitante');

    }

}