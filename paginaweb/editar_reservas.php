<?php
// Iniciar sesión para acceder a las variables de sesión
session_start();

// Incluir archivo de conexión a la base de datos
include "conexion.php";

// Verificar si el usuario es administrador, si no, redirigir al inicio
if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

// Procesar solicitudes POST para actualizar estado de reservas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener ID de la reserva y nuevo estado desde el formulario
    $id = $_POST['id'];
    $estado = mysqli_real_escape_string($conexion, $_POST['estado_reserva']);

    // Validar que el ID sea numérico
    if(!is_numeric($id)) {
        header("Location: administracion.php?error=ID inválido");
        exit();
    }

    // Actualizar estado de la reserva en la base de datos
    $sql = "UPDATE reserva SET estado = '$estado' WHERE id_reserva = $id";
    
    // Ejecutar actualización y redirigir con mensaje
    if (mysqli_query($conexion, $sql)) {
        header("Location: administracion.php?mensaje=Estado de reserva actualizado");
        exit();
    } else {
        header("Location: administracion.php?error=Error al actualizar reserva: " . mysqli_error($conexion));
        exit();
    }
}
?>