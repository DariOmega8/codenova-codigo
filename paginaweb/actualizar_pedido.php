<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_empleado']) && !isset($_SESSION['es_administrador'])) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pedido_id']) && isset($_POST['nuevo_estado'])) {
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];
    
    $sql_actualizar = "UPDATE pedido SET estado = '$nuevo_estado' WHERE pedido_id = $pedido_id";
    
    if (mysqli_query($conexion, $sql_actualizar)) {
        if ($nuevo_estado == 'entregado') {
        }
        
        header("Location: zona_staff.php?mensaje=Pedido actualizado correctamente");
    } else {
        header("Location: zona_staff.php?error=Error al actualizar pedido: " . mysqli_error($conexion));
    }
    exit();
} else {
    header("Location: zona_staff.php");
    exit();
}
?>