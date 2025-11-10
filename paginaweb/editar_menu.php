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

// Procesar solicitudes POST para operaciones con menús
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la acción a realizar desde el formulario
    $accion = $_POST['accion'];

    // Acción: Agregar nuevo menú
    if ($accion == "agregar_menu") {
        // Sanitizar datos del formulario
        $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        
        // Obtener ID del usuario administrador actual
        $usuario_id = $_SESSION['id_usuario'];
        
        // Consultar el ID del administrador en la base de datos
        $query_admin = "SELECT admin_id 
                       FROM admin 
                       WHERE usuario_id_usuario = $usuario_id 
                       LIMIT 1";
        
        $result_admin = mysqli_query($conexion, $query_admin);
        
        // Verificar si se encontró el administrador
        if ($result_admin && mysqli_num_rows($result_admin) > 0) {
            $admin = mysqli_fetch_assoc($result_admin);
            $admin_id = $admin['admin_id'];
            
            // Insertar nuevo menú en la base de datos
            $sql = "INSERT INTO menu (tipo, estado, admin_admin_id) 
                    VALUES ('$tipo', '$estado', $admin_id)";
            
            // Ejecutar inserción y redirigir con mensaje
            if(mysqli_query($conexion, $sql)) {
                header("Location: administracion.php?mensaje=Menú agregado correctamente");
                exit();
            } else {
                header("Location: administracion.php?error=Error al agregar menú: " . mysqli_error($conexion));
                exit();
            }
        } else {
            header("Location: administracion.php?error=No se encontró administrador para tu usuario");
            exit();
        }
    }

    // Acción: Editar menú existente
    if ($accion == "editar_menu") {
        // Obtener y sanitizar datos del formulario
        $id = $_POST['id'];
        $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        
        // Actualizar menú en la base de datos
        $sql = "UPDATE menu SET tipo = '$tipo', estado = '$estado' WHERE id_menu = $id";
        
        // Ejecutar actualización y redirigir con mensaje
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Menú actualizado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al actualizar menú: " . mysqli_error($conexion));
            exit();
        }
    }

    // Acción: Eliminar menú
    if ($accion == "eliminar_menu") {
        // Obtener ID del menú a eliminar
        $id = $_POST['id'];
        
        // Eliminar menú de la base de datos
        $sql = "DELETE FROM menu WHERE id_menu = $id";
        
        // Ejecutar eliminación y redirigir con mensaje
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Menú eliminado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al eliminar menú: " . mysqli_error($conexion));
            exit();
        }
    }
}
?>