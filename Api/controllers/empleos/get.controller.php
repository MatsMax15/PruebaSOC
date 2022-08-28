<?php

class Get_EmpleosController {

    // Obtener empleos
    static public function getEmpleos () {

        $response = Get_EmpleosModel::getEmpleos();

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: getEmpleos');

    }

}