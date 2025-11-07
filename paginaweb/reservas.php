<?php
session_start();
include "conexion.php";

if (isset($_POST['confirmar'])) {
    $cantidad = $_POST['personas'];
    $hora = $_POST['hora'];
    $fecha = $_POST['fecha'];
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $estado = "pendiente";
    $id_usuario = $_SESSION['id_usuario'];

    // Obtener el cliente_id del usuario (nuevo esquema)
    $sqlCliente = "SELECT cliente_id FROM cliente WHERE usuario_id_usuario = '$id_usuario'";
    $resultado = mysqli_query($conexion, $sqlCliente);
    $row = mysqli_fetch_assoc($resultado);

    if ($row) {
        $id_cliente = $row['cliente_id'];

        // Insertar reserva con el nuevo esquema
        $sqlReserva = "INSERT INTO reserva (hora_inicio, estado, cantidad, fecha, descripcion, cliente_cliente_id) 
                       VALUES ('$hora', '$estado', '$cantidad', '$fecha', '$descripcion', '$id_cliente')";
        
        if (mysqli_query($conexion, $sqlReserva)) {
            echo "Reserva realizada correctamente";
            header("Location: inicio.php");
            exit;
        } else {
            echo "Error al realizar la reserva: " . mysqli_error($conexion);
        }
    } else {
        echo "Cliente no encontrado";
    }
}
?>