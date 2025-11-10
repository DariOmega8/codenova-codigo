<?php
// Iniciar la sesión para acceder a las variables de sesión
session_start();

// Incluir el archivo de conexión a la base de datos
include "conexion.php";

// Verificar si el usuario tiene permisos de empleado o administrador
// Si no es empleado ni administrador, redirigir al inicio
if (!isset($_SESSION['es_empleado']) && !isset($_SESSION['es_administrador'])) {
    header("Location: inicio.php");
    exit();
}

// Procesar la actualización del estado del pedido cuando se recibe un formulario POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pedido_id']) && isset($_POST['nuevo_estado'])) {
    // Obtener y sanitizar los datos del formulario
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['nuevo_estado'];
    
    // Consulta SQL para actualizar el estado del pedido
    $sql_actualizar = "UPDATE pedido SET estado = '$nuevo_estado' WHERE pedido_id = $pedido_id";
    
    // Ejecutar la consulta y verificar si fue exitosa
    if (mysqli_query($conexion, $sql_actualizar)) {
        // Si el nuevo estado es "entregado", se podría agregar lógica adicional aquí
        // Por ejemplo: registrar en historial, notificar al cliente, etc.
        if ($nuevo_estado == 'entregado') {
            // Lógica adicional para pedidos entregados (pendiente de implementar)
        }
        
        // Redirigir con mensaje de éxito
        header("Location: zona_staff.php?mensaje=Pedido actualizado correctamente");
    } else {
        // Redirigir con mensaje de error si la consulta falla
        header("Location: zona_staff.php?error=Error al actualizar pedido: " . mysqli_error($conexion));
    }
    exit();
} else {
    // Si no se recibió una petición POST válida, redirigir a la zona staff
    header("Location: zona_staff.php");
    exit();
}
?>