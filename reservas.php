<?php

include 'conexion.php';

if (isset($_POST['confirmar'])) {

    if ( empty($_POST['personas']) || empty($_POST['hora']) ||empty($_POST['fecha']) ||empty($_POST['telefono'])  ) {
          echo "error en los datos"; 
    } else {

        $cantidad = $_POST['personas'];
        $hora = $_POST['hora'];
        $fecha = $_POST['fecha'];
        $telefono = $_POST['telefono'];

        $sql = "INSERT INTO reserva (hora, cantidad, fecha ) VALUES ('$hora', '$cantidad', '$fecha')";

        $sql2 = "INSERT INTO reserva telefono (telefono) VALUES ('$telefono')";
        
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