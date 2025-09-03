<?php
session_start();
include "conexion.php";

if (isset($_POST['confirmar'])) {
    $cantidad = $_POST['personas'];
    $hora = $_POST['hora'];
    $fecha = $_POST['fecha'];
    $estado = "pendiente";
    $id_usuario = $_SESSION['id_usuario'];

     $sqlCliente = "SELECT `id cliente` FROM cliente WHERE `usuario_id usuario` = '$id_usuario'";
     $resultado = mysqli_query($conexion, $sqlCliente);
     $row = mysqli_fetch_assoc($resultado);

     if ($row) {
         $id_cliente = $row['id cliente'];

         $sqlReserva = "INSERT INTO reserva (`hora de inicio`, cantidad, fecha, `cliente_id cliente`, `cliente_usuario_id usuario`) 
                        VALUES ('$hora', '$cantidad', '$fecha', '$id_cliente', '$id_usuario')";
         if (mysqli_query($conexion, $sqlReserva)) {
             echo " Reserva realizada correctamente";
             header("Location: inicio.php");
             exit;
         } else {
             echo " Error reserva: " . mysqli_error($conexion);
         }
     } else {
         echo " Cliente no encontrado";
     }
}

?>