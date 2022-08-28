<?php 

class ConfigModel {
    
    static function validateRoute ($step) {
        
        try {

            $route = $_SERVER['REQUEST_URI'];
            $route = substr($route, 1);
            $array_route = explode('/', $route);
            $count = count($array_route);
    
            // Route error
            foreach ($array_route as $key => $value) {
                if ( !$value ) throw new Exception('Route not found');
            }
    
            // Route users
            $module = !is_numeric($array_route[ $count - 1 ]) ? $array_route[ $count - 1 ] : $array_route[ $count - 2 ];
            $module = explode('?', $module)[0];
    
            if ( !$module ) throw new Exception('Route not found');

            $response = array(
                'status' => 200,
                'message' => 'Route found',
                'data' => $module,
            );

        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: validateRoute - ' . $step,
            );
            
        }

        return $response;
        
    }

}