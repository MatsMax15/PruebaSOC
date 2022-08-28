<?php

class Get_SolicitantesController {

    // Obtener solicitantes
    static public function getSolicitantes () {

        $response = Get_SolicitantesModel::getSolicitantes();

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: getSolicitantes');

    }

    // Obtener Solicitante
    static public function getSolicitante ($column, $value) {

        $response = Get_SolicitantesModel::getSolicitante($column, $value);

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: getSolicitante');

    }

}