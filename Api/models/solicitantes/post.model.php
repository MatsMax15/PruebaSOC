<?php

require_once 'models/config/connection.php';
require_once 'models/solicitantes/get.model.php';
require_once 'models/ingresos/post.model.php';

class Post_SolicitantesModel {

    // Crear Solicitante
    static public function createSolicitante ($params) {

        try {

            // Validar CURP
            $curp = $params['curp'];
            $validate = Get_SolicitantesModel::getSolicitante('curp', $curp);
            $validate = $validate['data'];
            
            if ( $validate ) {

                $response = array(
                    'status' => 204,
                    'message' => 'El CURP ya existe para el solicitante ' . $validate->nombre . ' ' . $validate->apellido_paterno . ' ' . $validate->apellido_materno,
                    'step' => 'model: createSolicitante',
                );
                
                return $response;
                
            }

            // Validar email repetido
            $email = $params['email'];
            $validate = Get_SolicitantesModel::getSolicitante('email', $email);
            $validate = $validate['data'];

            if ( $validate ) {
                
                $response = array(
                    'status' => 204,
                    'message' => 'El email ya existe para el solicitante ' . $validate->nombre . ' ' . $validate->apellido_paterno . ' ' . $validate->apellido_materno,
                    'step' => 'model: createSolicitante',
                );

                return $response;
                
            }

            // Validar email
            if ( $params['email'] && !filter_var($params['email'], FILTER_VALIDATE_EMAIL) ) {
                
                $response = array(
                    'status' => 204,
                    'message' => 'El email no es valido',
                    'step' => 'model: createSolicitante',
                );

                return $response;

            }

            // Validar edad (mayor a 18)
            $edad = $params['edad'];
            if ( $edad < 18 ) {
                
                $response = array(
                    'status' => 204,
                    'message' => 'El solitante debe ser mayor de edad',
                    'step' => 'model: createSolicitante',
                );

                return $response;

            }

            $fecha_registro = date('Y-m-d H:i:s');
            
            $anio = $params['anio'];
            $mes = str_pad($params['mes'], 2, "0", STR_PAD_LEFT);
            $dia = str_pad($params['dia'], 2, "0", STR_PAD_LEFT);

            $fecha_nacimiento = $anio . '-' . $mes . '-' . $dia;

            // Registro
            $sql = 'INSERT INTO solicitantes SET    nombre = :nombre, 
                                                    apellido_paterno = :apellido_paterno,
                                                    apellido_materno = :apellido_materno,
                                                    fecha_nacimiento = :fecha_nacimiento,
                                                    edad = :edad,
                                                    sexo = :sexo,
                                                    curp = :curp,
                                                    email = :email,
                                                    cp = :cp,
                                                    municipio = :municipio,
                                                    estado = :estado,
                                                    colonia = :colonia,
                                                    calle = :calle,
                                                    numero_exterior = :numero_exterior,
                                                    fecha_registro = :fecha_registro';

            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(':nombre', $params['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':apellido_paterno', $params['apellido_paterno'], PDO::PARAM_STR);
            $stmt->bindParam(':apellido_materno', $params['apellido_materno'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
            $stmt->bindParam(':edad', $params['edad'], PDO::PARAM_INT);
            $stmt->bindParam(':sexo', $params['genero'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $params['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $params['email'], PDO::PARAM_STR);
            $stmt->bindParam(':cp', $params['cp'], PDO::PARAM_STR);
            $stmt->bindParam(':municipio', $params['municipio'], PDO::PARAM_STR);
            $stmt->bindParam(':estado', $params['estado'], PDO::PARAM_STR);
            $stmt->bindParam(':colonia', $params['colonia'], PDO::PARAM_STR);
            $stmt->bindParam(':calle', $params['calle'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_exterior', $params['no_exterior'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_registro', $fecha_registro, PDO::PARAM_STR);

            $stmt->execute();

            // Obtener datos del registro
            $nuevoSolicitante = Get_SolicitantesModel::getSolicitante('curp', $params['curp']);

            $id_solicitante = $nuevoSolicitante['data']->id;
            $ingresos = json_decode($params['ingresos']);
            $registro_ingresos = Post_IngresosModel::createIngresos($ingresos, $id_solicitante);

            $response = array(
                'status' => 200,
                'message' => 'Solicitante registrado correctaemnte',
                'data' => $nuevoSolicitante['data'],
            );

        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: createSolicitante',
            );
            
        }

        return $response;

    }

}