<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $estado = mysqli_real_escape_string($conexion, $_POST['estado_reserva']);

    if(!is_numeric($id)) {
        header("Location: administracion.php?error=ID inválido");
        exit();
    }

    // Consulta actualizada
    $sql = "UPDATE reserva SET estado = '$estado' WHERE id_reserva = $id";
    
    if (mysqli_query($conexion, $sql)) {
        header("Location: administracion.php?mensaje=Estado de reserva actualizado");
        exit();
    } else {
        header("Location: administracion.php?error=Error al actualizar reserva: " . mysqli_error($conexion));
        exit();
    }
}
?>