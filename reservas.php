<?php

session_start();
include 'conexion.php';
session_start();

if (isset($_POST['confirmar'])) {
    if ( empty($_POST['personas']) || empty($_POST['hora']) || empty($_POST['fecha']) ) {
        echo "error en los datos"; 
    } else {
        $cantidad = $_POST['personas'];
        $hora = $_POST['hora'];
        $fecha = $_POST['fecha'];
        $id_cliente = $_SESSION['cliente_id cliente'];

        $sql = "INSERT INTO reserva (hora, cantidad, fecha, cliente_id cliente ) VALUES ('$hora', '$cantidad', '$fecha', '$id_cliente' )";

        $resultado = mysqli_query($conexion, $sql);
        
            if ($resultado) {
            header("Location: reservas.html");
            exit;

        } else {
            echo "Error en la reserva: " . mysqli_error($conexion);
        }

        }
    }
?>