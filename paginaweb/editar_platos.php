<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['es_administrador']) || !$_SESSION['es_administrador']) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    if ($accion == "agregar_plato") {
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio = $_POST['precio'];
        $menu_id_menu = $_POST['menu_id_menu'];

        if(!is_numeric($precio)) {
            header("Location: administracion.php?error=El precio debe ser un número");
            exit();
        }

        // Manejo de imagen (si se sube)
        $imagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $imagen = mysqli_real_escape_string($conexion, file_get_contents($_FILES['imagen']['tmp_name']));
        } else {
            // Si no se sube imagen, usar una imagen por defecto
            // Primero intentamos cargar una imagen por defecto del sistema
            $imagen_default_path = "estilos/imagenes/balatro.png";
            if (file_exists($imagen_default_path)) {
                $imagen = mysqli_real_escape_string($conexion, file_get_contents($imagen_default_path));
            } else {
                // Si no existe la imagen por defecto, creamos un BLOB vacío
                header("Location: administracion.php?error=Debe subir una imagen para el plato");
                exit();
            }
        }

        // Consulta CORREGIDA: usando el nombre correcto de la columna
        $sql = "INSERT INTO plato (nombre, descripcion, precio, `menu_id menu`, imagen)
                VALUES ('$nombre', '$descripcion', $precio, $menu_id_menu, '$imagen')";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Plato agregado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al agregar plato: " . mysqli_error($conexion));
            exit();
        }
    }

    if ($accion == "editar_plato") {
        $id = $_POST['id'];
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $precio = $_POST['precio'];
        
        // Consulta actualizada
        $sql = "UPDATE plato 
                SET nombre = '$nombre', 
                    precio = $precio
                WHERE plato_id = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Plato actualizado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al actualizar plato: " . mysqli_error($conexion));
            exit();
        }
    }

    if ($accion == "eliminar_plato") {
        $id = $_POST['id'];
        
        // Consulta actualizada
        $sql = "DELETE FROM plato WHERE plato_id = $id";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Plato eliminado correctamente");
            exit();
        } else {
            header("Location: administracion.php?error=Error al eliminar plato: " . mysqli_error($conexion));
            exit();
        }
    }
}
?>