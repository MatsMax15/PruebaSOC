<?php

class Get_IngresosController {

    // Obtener ingresos
    static public function getIngresos ($params) {

        $response = Get_IngresosModel::getIngresos($params);

        $return = new ConfigController();
        $return->dataResponse($response, 'controller: getIngresos');

    }

}