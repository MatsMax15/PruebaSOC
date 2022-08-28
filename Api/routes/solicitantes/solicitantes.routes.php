<?php

$method = $_SERVER['REQUEST_METHOD'];

// Peticiones GET
if ( $method === 'GET' ) include 'services/get.php';

// Peticiones POST
if ( $method === 'POST' ) include 'services/post.php';

// Peticiones PUT
if ( $method === 'PUT' ) include 'services/put.php';

// Peticiones DELETE
if ( $method === 'DELETE' ) include 'services/delete.php';