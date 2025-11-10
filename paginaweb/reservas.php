<?php
// Inicia la sesión para acceder a las variables de sesión del usuario
session_start();
// Incluye el archivo de conexión a la base de datos
include "conexion.php";

// Verifica si se ha enviado el formulario de confirmación de reserva
if (isset($_POST['confirmar'])) {
    // Recoge los datos del formulario de reserva
    $cantidad = $_POST['personas'];
    $hora = $_POST['hora'];
    $fecha = $_POST['fecha'];
    // La descripción es opcional, si no existe se asigna cadena vacía
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    // Estado inicial de la reserva
    $estado = "pendiente";
    // Obtiene el ID del usuario desde la sesión
    $id_usuario = $_SESSION['id_usuario'];

    // Consulta SQL para obtener el ID del cliente basado en el ID de usuario
    // Esto es necesario porque en el nuevo esquema, cliente y usuario están separados
    $sqlCliente = "SELECT cliente_id FROM cliente WHERE usuario_id_usuario = '$id_usuario'";
    $resultado = mysqli_query($conexion, $sqlCliente);
    $row = mysqli_fetch_assoc($resultado);

    // Verifica si se encontró el cliente asociado al usuario
    if ($row) {
        // Obtiene el ID del cliente desde el resultado de la consulta
        $id_cliente = $row['cliente_id'];

        // Consulta SQL para insertar la reserva en la base de datos
        // Usa el nuevo esquema de base de datos con las columnas actualizadas
        $sqlReserva = "INSERT INTO reserva (hora_inicio, estado, cantidad, fecha, descripcion, cliente_cliente_id) 
                       VALUES ('$hora', '$estado', '$cantidad', '$fecha', '$descripcion', '$id_cliente')";
        
        // Ejecuta la consulta de inserción de la reserva
        if (mysqli_query($conexion, $sqlReserva)) {
            // Muestra mensaje de éxito y redirige al usuario
            echo "Reserva realizada correctamente";
            header("Location: inicio.php");
            exit;
        } else {
            // Manejo de error en la inserción de la reserva
            echo "Error al realizar la reserva: " . mysqli_error($conexion);
        }
    } else {
        // Manejo de error cuando no se encuentra el cliente asociado al usuario
        echo "Cliente no encontrado";
    }
}
?>