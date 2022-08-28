<?php

class Delete_SolicitantesController {

    // Update user
    static public function deleteSolicitante ($params) {

        $response = Delete_SolicitantesModel::deleteSolicitante($params);

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: deleteSolicitante');

    }

}