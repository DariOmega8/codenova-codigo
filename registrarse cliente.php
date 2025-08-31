<?php

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $gmail = $_POST['gmail'];
    $pass = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    $nacionalidad = $_POST['nacionalidad'];
    $telefono = $_POST['telefono'];

    // Insertar usuario
    $sqlUsuario = "INSERT INTO `usuario`(`nombre`, `fecha de nacimiento`, `gmail`, `contraseña`) 
                   VALUES ("$nombre", "$fecha", "$gmail", "$pass")";
    $stmt = $conn->prepare($sqlUsuario);
    $stmt->bind_param("ssss", $nombre, $fecha, $gmail, $pass);

    if ($stmt->execute()) {
        $idUsuario = $conn->insert_id;

        // Insertar cliente
        $sqlCliente = "INSERT INTO `cliente` (`nacionalidad`, `usuario_id usuario`) VALUES ("$nacionalidad", "$idUsuario")";
        $stmt2 = $conn->prepare($sqlCliente);
        $stmt2->bind_param("si", $nacionalidad, $idUsuario);

        if ($stmt2->execute()) {
            $idCliente = $conn->insert_id;

            // Insertar telefono
            $sqlTel = "INSERT INTO `telefono` (`telefono`, `cliente_id cliente`, `cliente_usuario_id usuario`) 
                       VALUES ("$telefono", "$idCliente", "$idUsuario")";
            $stmt3 = $conn->prepare($sqlTel);
            $stmt3->bind_param("sii", $telefono, $idCliente, $idUsuario);

            if ($stmt3->execute()) {
                echo " Registro exitoso";
            } else {
                echo "Error teléfono: " . $stmt3->error;
            }
        } else {
            echo "Error cliente: " . $stmt2->error;
        }
    } else {
        echo "Error usuario: " . $stmt->error;
    }
}

?>