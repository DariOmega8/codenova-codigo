<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Iniciar sesión para almacenar datos del usuario
session_start();

// Verificar si se enviaron datos de inicio de sesión
if (isset($_POST['gmail']) || isset($_POST['contraseña'])) {
    // Obtener credenciales del formulario
    $gmail = $_POST['gmail'];
    $contrasena = $_POST['contraseña'];

    // Consulta para obtener información del usuario y sus roles
    $sql = "SELECT u.id_usuario, u.nombre, u.contraseña, u.apellido, u.nacionalidad,
                   a.admin_id,
                   e.empleado_id,
                   c.cliente_id
            FROM usuario u 
            LEFT JOIN admin a ON u.id_usuario = a.usuario_id_usuario
            LEFT JOIN empleado e ON u.id_usuario = e.usuario_id_usuario
            LEFT JOIN cliente c ON u.id_usuario = c.usuario_id_usuario
            WHERE u.gmail = '$gmail'";
    
    // Ejecutar consulta
    $resultado = mysqli_query($conexion, $sql);

    // Verificar si se encontró el usuario
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);

        // Verificar contraseña (comparación directa - considerar usar password_verify() si se usa hash)
        if ($contrasena === $row['contraseña']) { 
            // Establecer variables de sesión con información del usuario
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellido'] = $row['apellido'];
            $_SESSION['nacionalidad'] = $row['nacionalidad'];
            
            // Determinar y establecer roles del usuario
            $_SESSION['es_administrador'] = !is_null($row['admin_id']);
            $_SESSION['es_empleado'] = !is_null($row['empleado_id']);
            $_SESSION['es_cliente'] = !is_null($row['cliente_id']);
            
            // Establecer IDs específicos según el rol
            if (!is_null($row['admin_id'])) {
                $_SESSION['admin_id'] = $row['admin_id'];
            }
            if (!is_null($row['empleado_id'])) {
                $_SESSION['empleado_id'] = $row['empleado_id'];
            }
            if (!is_null($row['cliente_id'])) {
                $_SESSION['cliente_id'] = $row['cliente_id'];
            }
            
            // Redirigir según el rol del usuario
            if ($_SESSION['es_administrador']) {
                header("Location: administracion.php");
            } elseif ($_SESSION['es_empleado']) {
                header("Location: zona_staff.php");
            } else {
                header("Location: inicio.php");
            }
            exit();
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta";
        }
    } else {
        // Usuario no encontrado
        echo "Usuario no encontrado";
    }
}
?>