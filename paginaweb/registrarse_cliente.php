<?php
// Incluye el archivo de conexión a la base de datos
include 'conexion.php';
// Inicia la sesión para almacenar variables de sesión del usuario
session_start();

// Verifica si se ha enviado el formulario (botón submit presionado)
if (isset($_POST['submit'])) {
    // Valida que todos los campos obligatorios estén completos
    if (
        empty($_POST['gmail']) || empty($_POST['contraseña']) || empty($_POST['nombre']) 
        || empty($_POST['apellido']) || empty($_POST['fecha_nacimiento']) 
        || empty($_POST['nacionalidad']) || empty($_POST['telefono'])
    ) {
        // Muestra mensaje de error si faltan campos obligatorios
        echo "Complete todos los campos";
    } else {
        // Asigna los valores del formulario a variables locales
        $gmail = $_POST['gmail'];
        $contrasena = $_POST['contraseña']; 
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $nacionalidad = $_POST['nacionalidad'];
        $telefono = $_POST['telefono'];
        // Preferencias es opcional, si no existe se asigna cadena vacía
        $preferencias = isset($_POST['preferencias']) ? $_POST['preferencias'] : '';

        // Consulta SQL para insertar el usuario en la tabla 'usuario'
        $sqlUsuario = "INSERT INTO usuario (nombre, apellido, fecha_nac, gmail, contraseña, fecha_registro, nacionalidad) 
                       VALUES ('$nombre', '$apellido', '$fecha_nacimiento', '$gmail', '$contrasena', NOW(), '$nacionalidad')";
        
        // Ejecuta la consulta de inserción del usuario
        if (mysqli_query($conexion, $sqlUsuario)) {
            // Obtiene el ID del usuario recién insertado
            $id_usuario = mysqli_insert_id($conexion);
          
            // Consulta SQL para insertar el cliente en la tabla 'cliente'
            $sqlCliente = "INSERT INTO cliente (preferencias, usuario_id_usuario) 
                           VALUES ('$preferencias', '$id_usuario')";
            
            // Ejecuta la consulta de inserción del cliente
            if (mysqli_query($conexion, $sqlCliente)) {
                // Obtiene el ID del cliente recién insertado
                $id_cliente = mysqli_insert_id($conexion);
                // Almacena el ID del cliente en la sesión
                $_SESSION['id_cliente'] = $id_cliente;

                // Consulta SQL para insertar el teléfono en la tabla 'telefono'
                $sqlTelefono = "INSERT INTO telefono (telefono, cliente_cliente_id) 
                                VALUES ('$telefono', '$id_cliente')";
                
                // Ejecuta la consulta de inserción del teléfono
                if (mysqli_query($conexion, $sqlTelefono)) {
                    // Almacena los datos del usuario en variables de sesión
                    $_SESSION['id_usuario'] = $id_usuario;
                    $_SESSION['nombre'] = $nombre;
                    $_SESSION['apellido'] = $apellido;
                    $_SESSION['nacionalidad'] = $nacionalidad;
                    $_SESSION['es_cliente'] = true;
                    
                    // Muestra mensaje de éxito y redirige al usuario
                    echo "Registro exitoso";
                    header("Location: inicio.php");
                    exit;
                } else {
                    // Manejo de error en la inserción del teléfono
                    echo "Error al registrar teléfono: " . mysqli_error($conexion);
                }
            } else {
                // Manejo de error en la inserción del cliente
                echo "Error al registrar cliente: " . mysqli_error($conexion);
            }
        } else {
            // Manejo de error en la inserción del usuario
            echo "Error al registrar usuario: " . mysqli_error($conexion);
        }
    }
}
?>