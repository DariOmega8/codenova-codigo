<?php
session_start();
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $estado = $_POST['estado'];

    $sql = "UPDATE reserva SET estado='$estado' WHERE `id reserva`=$id";
    if (mysqli_query($conexion, $sql)) {
        echo "Estado de la reserva actualizado.";
    } else {
        echo "Error: " . mysqli_error($conexion);
    }
}
?>
