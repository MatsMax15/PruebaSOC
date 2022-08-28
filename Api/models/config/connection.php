<?php

class Connection {

    static public function infoDataBase() {

        // $dbInfo = array(
        //     'host' => 'localhost',
        //     'database' => 'bd_soc',
        //     'user' => 'root',
        //     'pass' => ''
        // );

        $dbInfo = array(
            'host' => 'db5009888639.hosting-data.io',
            'database' => 'dbs8384500',
            'user' => 'dbu2813132',
            'pass' => '$151293Lamf'
        );

        return $dbInfo;
        
    }

    static public function connect() {
        
        try {

            $dbInfo = Connection::infoDataBase();
            $connect = new PDO('mysql:host=' . $dbInfo['host'] . ';dbname=' . $dbInfo['database'], $dbInfo['user'], $dbInfo['pass']);
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connect->exec("SET NAMES utf8");

        } catch ( PDOException $e ) {
            
            die('Error: ' . $e->getMessage());

        }

        return $connect;

    }

}