<?php

$server = "localhost";
$user = "root";
$password = "";
$db = "restaurante";

$conexion = new mysqli($server, $user, $password, $db);

if ($conexion->connect_error) {
    die("Conexion fallida" . $conexion->connect_error);
}

?>