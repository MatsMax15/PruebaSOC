<?php

require_once 'models/config/config.model.php';

class ConfigController {

    // Response
    public function dataResponse ($response, $step) {
  
        if ( $response['status'] !== 200 ) {
            return ConfigController::dataError($response['message'], $response['status'], $step);
        }

        $json = array(
            'status' => $response['status'],
            'message' => $response['message'],
            'data' => $response['data'],
            'step' => $step,
        );

        echo json_encode($json, http_response_code($json['status']));

    }

    // Data Error
    static public function dataError ($msg, $status, $step) {

        $json = array(
            'status' => $status,
            'message' => $msg,
            'step' => 'controller: dataError' . ' - ' . $step,
        );

        echo json_encode($json);

    }

    static public function validateRoute ($step) {

        return ConfigModel::validateRoute($step);

    }

}