<?php 

class Post_SolicitantesController {

    // Create Solicitante
    static public function createSolicitante ($params) {

        $response = Post_SolicitantesModel::createSolicitante($params);

        echo json_encode($response);
        return;

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: createSolicitante');

    }

}