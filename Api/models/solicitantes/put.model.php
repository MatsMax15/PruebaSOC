<?php

require_once 'models/config/connection.php';
require_once 'models/solicitantes/get.model.php';
require_once 'models/ingresos/post.model.php';

class Put_SolicitantesModel {

    // Update user
    static public function updateSolicitante ($params, $data) {

        try {

            $column = $params['column'];
            $value = $params['value'];

            // Validar CURP
            $curp = $data['curp'];
            $sql = 'SELECT nombre, apellido_paterno, apellido_materno FROM solicitantes WHERE curp = :curp AND id != :id';
            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(':curp', $curp, PDO::PARAM_STR);
            $stmt->bindParam(':id', $value, PDO::PARAM_STR);
            $stmt->execute();
            $data_solicitante = $stmt->fetchObject();
            
            if ( $data_solicitante ) {
            
                $response = array(
                    'status' => 400,
                    'message' => 'El CURP ya se encuentra registrado para el solicitante ' . $data_solicitante->nombre . ' ' . $data_solicitante->apellido_paterno . ' ' . $data_solicitante->apellido_materno,
                    'step' => 'model: updateSolicitante',
                );
                return $response;

            }

            // Validar email repetido
            $email = $data['email'];
            $validate = Get_SolicitantesModel::getSolicitante('email', $email);
            $validate = $validate['data'];

            if ( $validate->id !== $value ) {
                
                $response = array(
                    'status' => 204,
                    'message' => 'El email ya existe para el solicitante ' . $validate->nombre . ' ' . $validate->apellido_paterno . ' ' . $validate->apellido_materno,
                    'step' => 'model: createSolicitante',
                );
                return $response;
                
            }

            // Validar email
            if ( $data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ) {
                
                $response = array(
                    'status' => 204,
                    'message' => 'El email no es valido',
                    'step' => 'model: createSolicitante',
                );
                return $response;

            }

            // Validar edad (mayor a 18)
            $edad = $data['edad'];
            if ( $edad < 18 ) {
                
                $response = array(
                    'status' => 204,
                    'message' => 'El solitante debe ser mayor de edad',
                    'step' => 'model: createSolicitante',
                );
                return $response;

            }

            $anio = $data['anio'];
            $mes = str_pad($data['mes'], 2, "0", STR_PAD_LEFT);
            $dia = str_pad($data['dia'], 2, "0", STR_PAD_LEFT);

            $fecha_nacimiento = $anio . '-' . $mes . '-' . $dia;

            // Registro
            $sql = 'UPDATE solicitantes SET nombre = :nombre, 
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
                                            numero_exterior = :numero_exterior
                                        WHERE 
                                            id = :id';

            $stmt = Connection::connect()->prepare($sql);
            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':apellido_paterno', $data['apellido_paterno'], PDO::PARAM_STR);
            $stmt->bindParam(':apellido_materno', $data['apellido_materno'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento, PDO::PARAM_STR);
            $stmt->bindParam(':edad', $data['edad'], PDO::PARAM_INT);
            $stmt->bindParam(':sexo', $data['sexo'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':cp', $data['cp'], PDO::PARAM_STR);
            $stmt->bindParam(':municipio', $data['municipio'], PDO::PARAM_STR);
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
            $stmt->bindParam(':colonia', $data['colonia'], PDO::PARAM_STR);
            $stmt->bindParam(':calle', $data['calle'], PDO::PARAM_STR);
            $stmt->bindParam(':numero_exterior', $data['numero_exterior'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $value, PDO::PARAM_STR);
            
            $stmt->execute();

            // Obtener datos del registro
            $solicitanteModificado = Get_SolicitantesModel::getSolicitante('id', $value);

            $ingresos = json_decode($data['ingresos']);
            $registro_ingresos = Post_IngresosModel::createIngresos($ingresos, $value);

            $response = array(
                'status' => 200,
                'message' => 'El solicitante ' . $solicitanteModificado['data']->nombre . ' ' . $solicitanteModificado['data']->apellido_paterno . ' ' . $solicitanteModificado['data']->apellido_materno . 'se ha actualizado correctamente',
                'data' => $registro_ingresos,
            );
            
        } catch ( Exception $e ) {

            $response = array(
                'status' => 500,
                'message' => $e->getMessage(),
                'step' => 'model: updateSolicitante',
            );

        }

        return $response;

    }

}