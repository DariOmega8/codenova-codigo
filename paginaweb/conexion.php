<?php

$_SERVER = "localhost";
$_USER = "root";
$_PASSWORD = "";
$db = "mydb";

$conexion = new mysqli($_SERVER, $_USER, $_PASSWORD, $db);

if ($conexion->connect_error) {
    die("Conexion fallida" . $conexion->connect_error);
}

?>