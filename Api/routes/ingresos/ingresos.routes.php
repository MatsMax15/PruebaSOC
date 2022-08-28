<?php

$method = $_SERVER['REQUEST_METHOD'];

// Peticiones GET
if ( $method === 'GET' ) include 'services/get.php';