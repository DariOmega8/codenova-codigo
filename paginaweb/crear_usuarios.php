<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // PRIMERO: Manejar eliminación de usuarios
    if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar_usuario') {
        $id = intval($_POST['id']);
        $tipo = $_POST['tipo'];
        
        // Verificar que no sea el propio administrador
        if ($id == $_SESSION['id_usuario']) {
            header("Location: administracion.php?error=No puedes eliminar tu propio usuario");
            exit();
        }
        
        // Iniciar transacción para mayor seguridad
        mysqli_begin_transaction($conexion);
        
        try {
            // Eliminar de la tabla específica primero
            if ($tipo == 'administrador') {
                $sql_eliminar = "DELETE FROM admin WHERE usuario_id_usuario = $id";
            } elseif ($tipo == 'empleado') {
                $sql_eliminar = "DELETE FROM empleado WHERE usuario_id_usuario = $id";
            }
            
            if (!mysqli_query($conexion, $sql_eliminar)) {
                throw new Exception("Error al eliminar de tabla específica: " . mysqli_error($conexion));
            }
            
            // Luego eliminar de usuario
            $sql = "DELETE FROM usuario WHERE id_usuario = $id";
            
            if (!mysqli_query($conexion, $sql)) {
                throw new Exception("Error al eliminar usuario: " . mysqli_error($conexion));
            }
            
            // Confirmar transacción
            mysqli_commit($conexion);
            header("Location: administracion.php?mensaje=Usuario eliminado correctamente");
            exit();
            
        } catch (Exception $e) {
            // Revertir en caso de error
            mysqli_rollback($conexion);
            header("Location: administracion.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
    
    // SEGUNDO: Manejar creación de usuarios (solo si no es eliminación)
    if (isset($_POST['tipo']) && (!isset($_POST['accion']) || $_POST['accion'] != 'eliminar_usuario')) {
        // Datos del formulario
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
        $fecha_nac = $_POST['fecha_nac'];
        $gmail = mysqli_real_escape_string($conexion, $_POST['gmail']);
        $password = mysqli_real_escape_string($conexion, $_POST['password']);
        $nacionalidad = mysqli_real_escape_string($conexion, $_POST['nacionalidad']);
        $tipo = $_POST['tipo'];
        $salario = isset($_POST['salario']) ? floatval($_POST['salario']) : 0;

        // Validaciones básicas
        if (empty($nombre) || empty($apellido) || empty($fecha_nac) || empty($gmail) || empty($password) || empty($nacionalidad) || empty($tipo)) {
            header("Location: administracion.php?error=Todos los campos son requeridos");
            exit();
        }

        // Validar salario para empleados
        if ($tipo == "empleado" && $salario <= 0) {
            header("Location: administracion.php?error=El salario es requerido para empleados");
            exit();
        }

        // Verificar si el email ya existe
        $checkEmail = mysqli_query($conexion, "SELECT id_usuario FROM usuario WHERE gmail = '$gmail'");
        if (mysqli_num_rows($checkEmail) > 0) {
            header("Location: administracion.php?error=El email ya está registrado");
            exit();
        }

        // Hash de la contraseña (más seguro)
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // INSERT en usuario - NUEVO esquema
        $sqlUsuario = "INSERT INTO usuario (nombre, apellido, fecha_nac, gmail, contraseña, fecha_registro, nacionalidad) 
                       VALUES ('$nombre', '$apellido', '$fecha_nac', '$gmail', '$password_hash', NOW(), '$nacionalidad')";
        
        if (mysqli_query($conexion, $sqlUsuario)) {
            $id_usuario = mysqli_insert_id($conexion);
            $success = true;
            $error_msg = "";

            // Crear en la tabla específica según el tipo
            if ($tipo == "administrador") {
                $sql = "INSERT INTO admin (ultima_conexion, usuario_id_usuario) 
                        VALUES (NOW(), $id_usuario)";
                if (!mysqli_query($conexion, $sql)) {
                    $success = false;
                    $error_msg = "Error al crear administrador: " . mysqli_error($conexion);
                }
            } elseif ($tipo == "empleado") {
                $sql = "INSERT INTO empleado (estado, salario, usuario_id_usuario) 
                        VALUES ('activo', $salario, $id_usuario)";
                if (!mysqli_query($conexion, $sql)) {
                    $success = false;
                    $error_msg = "Error al crear empleado: " . mysqli_error($conexion);
                }
            }

            if ($success) {
                header("Location: administracion.php?mensaje=Usuario creado correctamente");
                exit();
            } else {
                // Si falló la inserción en la tabla específica, eliminar el usuario creado
                mysqli_query($conexion, "DELETE FROM usuario WHERE id_usuario = $id_usuario");
                header("Location: administracion.php?error=" . urlencode($error_msg));
                exit();
            }
        } else {
            header("Location: administracion.php?error=Error al crear usuario: " . urlencode(mysqli_error($conexion)));
            exit();
        }
    }
}

// Si llega aquí sin procesar POST, redirigir
header("Location: administracion.php");
exit();
?>