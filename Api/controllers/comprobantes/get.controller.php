<?php

class Get_ComprobantesController {

    // Obtener comprobantes
    static public function getComprobantes () {

        $response = Get_ComprobantesModel::getComprobantes();

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: getComprobantes');

    }

}