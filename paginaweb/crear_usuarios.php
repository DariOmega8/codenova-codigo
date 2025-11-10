<?php
/**
 * archivo: crear_usuarios.php
 * Descripción: Maneja la creación y eliminación de usuarios en el sistema
 * Funcionalidades:
 * - Crear nuevos usuarios (empleados y administradores)
 * - Eliminar usuarios existentes
 * - Validaciones de seguridad y integridad de datos
 */

// Iniciar sesión para verificar permisos de administrador
session_start();

// Incluir archivo de conexión a la base de datos
include "conexion.php";

// Verificar si el usuario actual es administrador
// Si no es administrador, redirigir al inicio por seguridad
if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

// =============================================================================
// PROCESAMIENTO DE SOLICITUDES POST (CREAR O ELIMINAR USUARIOS)
// =============================================================================

// Verificar que la solicitud sea mediante POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // =========================================================================
    // SECCIÓN 1: ELIMINACIÓN DE USUARIOS
    // =========================================================================
    
    if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar_usuario') {
        // Obtener y sanitizar datos del formulario
        $id = intval($_POST['id']); // Convertir a entero para seguridad
        $tipo = $_POST['tipo']; // Tipo de usuario (administrador/empleado)
        
        // Medida de seguridad: Evitar que un administrador se elimine a sí mismo
        if ($id == $_SESSION['id_usuario']) {
            header("Location: administracion.php?error=No puedes eliminar tu propio usuario");
            exit();
        }
        
        // INICIAR TRANSACCIÓN PARA GARANTIZAR INTEGRIDAD DE DATOS
        // Las transacciones aseguran que todas las operaciones se completen o ninguna
        mysqli_begin_transaction($conexion);
        
        try {
            // Paso 1: Eliminar de la tabla específica (admin o empleado) primero
            if ($tipo == 'administrador') {
                $sql_eliminar = "DELETE FROM admin WHERE usuario_id_usuario = $id";
            } elseif ($tipo == 'empleado') {
                $sql_eliminar = "DELETE FROM empleado WHERE usuario_id_usuario = $id";
            }
            
            // Ejecutar eliminación en tabla específica
            if (!mysqli_query($conexion, $sql_eliminar)) {
                throw new Exception("Error al eliminar de tabla específica: " . mysqli_error($conexion));
            }
            
            // Paso 2: Eliminar de la tabla usuario (eliminación en cascada por FK)
            $sql = "DELETE FROM usuario WHERE id_usuario = $id";
            
            if (!mysqli_query($conexion, $sql)) {
                throw new Exception("Error al eliminar usuario: " . mysqli_error($conexion));
            }
            
            // CONFIRMAR TRANSACCIÓN - Todas las operaciones fueron exitosas
            mysqli_commit($conexion);
            header("Location: administracion.php?mensaje=Usuario eliminado correctamente");
            exit();
            
        } catch (Exception $e) {
            // REVERTIR TRANSACCIÓN - Algo falló, deshacer todos los cambios
            mysqli_rollback($conexion);
            header("Location: administracion.php?error=" . urlencode($e->getMessage()));
            exit();
        }
    }
    
    // =========================================================================
    // SECCIÓN 2: CREACIÓN DE NUEVOS USUARIOS
    // =========================================================================
    
    // Verificar que es una solicitud de creación (no eliminación)
    if (isset($_POST['tipo']) && (!isset($_POST['accion']) || $_POST['accion'] != 'eliminar_usuario')) {
        
        // OBTENER Y SANITIZAR DATOS DEL FORMULARIO
        // mysqli_real_escape_string previene inyecciones SQL
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
        $fecha_nac = $_POST['fecha_nac']; // No necesita escape, es fecha
        $gmail = mysqli_real_escape_string($conexion, $_POST['gmail']);
        $password = mysqli_real_escape_string($conexion, $_POST['password']);
        $nacionalidad = mysqli_real_escape_string($conexion, $_POST['nacionalidad']);
        $tipo = $_POST['tipo']; // Tipo de usuario a crear
        $salario = isset($_POST['salario']) ? floatval($_POST['salario']) : 0; // Solo para empleados

        // =====================================================================
        // VALIDACIONES DE SEGURIDAD E INTEGRIDAD DE DATOS
        // =====================================================================
        
        // Validar que todos los campos requeridos estén completos
        if (empty($nombre) || empty($apellido) || empty($fecha_nac) || 
            empty($gmail) || empty($password) || empty($nacionalidad) || empty($tipo)) {
            header("Location: administracion.php?error=Todos los campos son requeridos");
            exit();
        }

        // Validación específica para empleados: salario debe ser positivo
        if ($tipo == "empleado" && $salario <= 0) {
            header("Location: administracion.php?error=El salario es requerido para empleados");
            exit();
        }

        // Verificar si el email ya existe en la base de datos
        // Previene duplicación de usuarios
        $checkEmail = mysqli_query($conexion, "SELECT id_usuario FROM usuario WHERE gmail = '$gmail'");
        if (mysqli_num_rows($checkEmail) > 0) {
            header("Location: administracion.php?error=El email ya está registrado");
            exit();
        }

        // =====================================================================
        // PROCESAMIENTO SEGURO DE LA CONTRASEÑA
        // =====================================================================
        
        // Hash de la contraseña usando algoritmo seguro
        // Nunca almacenar contraseñas en texto plano
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // =====================================================================
        // INSERCIÓN EN LA BASE DE DATOS
        // =====================================================================
        
        // Paso 1: Insertar en la tabla usuario (tabla base)
        $sqlUsuario = "INSERT INTO usuario (nombre, apellido, fecha_nac, gmail, contraseña, fecha_registro, nacionalidad) 
                       VALUES ('$nombre', '$apellido', '$fecha_nac', '$gmail', '$password_hash', NOW(), '$nacionalidad')";
        
        // Ejecutar inserción del usuario base
        if (mysqli_query($conexion, $sqlUsuario)) {
            // Obtener el ID del usuario recién creado
            $id_usuario = mysqli_insert_id($conexion);
            $success = true; // Bandera para controlar éxito
            $error_msg = ""; // Variable para mensajes de error

            // =================================================================
            // CREACIÓN EN TABLA ESPECÍFICA SEGÚN TIPO DE USUARIO
            // =================================================================
            
            if ($tipo == "administrador") {
                // Insertar en tabla admin
                $sql = "INSERT INTO admin (ultima_conexion, usuario_id_usuario) 
                        VALUES (NOW(), $id_usuario)";
                if (!mysqli_query($conexion, $sql)) {
                    $success = false;
                    $error_msg = "Error al crear administrador: " . mysqli_error($conexion);
                }
            } elseif ($tipo == "empleado") {
                // Insertar en tabla empleado con estado activo y salario
                $sql = "INSERT INTO empleado (estado, salario, usuario_id_usuario) 
                        VALUES ('activo', $salario, $id_usuario)";
                if (!mysqli_query($conexion, $sql)) {
                    $success = false;
                    $error_msg = "Error al crear empleado: " . mysqli_error($conexion);
                }
            }

            // =================================================================
            // MANEJO DE RESULTADOS FINALES
            // =================================================================
            
            if ($success) {
                // ÉXITO: Usuario creado completamente
                header("Location: administracion.php?mensaje=Usuario creado correctamente");
                exit();
            } else {
                // FALLO: Eliminar usuario base si falló la inserción específica
                // Esto previene usuarios huérfanos en la base de datos
                mysqli_query($conexion, "DELETE FROM usuario WHERE id_usuario = $id_usuario");
                header("Location: administracion.php?error=" . urlencode($error_msg));
                exit();
            }
        } else {
            // Error en la inserción del usuario base
            header("Location: administracion.php?error=Error al crear usuario: " . urlencode(mysqli_error($conexion)));
            exit();
        }
    }
}

// =============================================================================
// REDIRECCIÓN POR DEFECTO
// =============================================================================

// Si se accede al archivo sin una solicitud POST válida, redirigir al panel
header("Location: administracion.php");
exit();
?>