<?php

include 'conexion.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die(" Debes iniciar sesión para reservar");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cantidad = $_POST['personas'];
    $hora = $_POST['hora'];
    $fecha = $_POST['fecha'];
    $estado = "pendiente";
    $id_usuario = $_SESSION['id_usuario'];

    // Obtener id_cliente desde usuario
    $sqlCliente = "SELECT `id cliente` FROM cliente WHERE `usuario_id usuario` = ?";
    $stmt = $conexion->prepare($sqlCliente);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();

    if ($row) {
        $id_cliente = $row['id cliente'];

        $sqlReserva = "INSERT INTO reserva (`hora de inicio`, estado, cantidad, fecha, `cliente_id cliente`, `cliente_usuario_id usuario`) 
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt2 = $conexion->prepare($sqlReserva);
        $stmt2->bind_param("ssdsii", $hora, $estado, $cantidad, $fecha, $id_cliente, $id_usuario);

        if ($stmt2->execute()) {
            echo " reserva realizada correctamente";
        } else {
            echo " Error reserva: " . $stmt2->error;
        }
    } else {
        echo " Cliente no encontrado";
    }
}
?>