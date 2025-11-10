```php
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

// Configuración para subida de imágenes - guardar en carpeta en lugar de BLOB
$directorio_imagenes = "imagenes_platos/";
$mensaje = "";
$error = "";

// Crear directorio para imágenes si no existe
if (!file_exists($directorio_imagenes)) {
    mkdir($directorio_imagenes, 0777, true);
}

// Procesar solicitudes POST para operaciones con platos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    // Acción: Agregar nuevo plato
    if ($accion == "agregar_plato") {
        // Sanitizar datos del formulario
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
        $precio = $_POST['precio'];
        $menu_id_menu = $_POST['menu_id_menu'];

        // Validar que el precio sea numérico
        if(!is_numeric($precio)) {
            header("Location: administracion.php?error=El precio debe ser un número");
            exit();
        }

        // Procesar subida de imagen
        $nombre_imagen = "";
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
            $archivo_temporal = $_FILES['imagen']['tmp_name'];
            $nombre_original = $_FILES['imagen']['name'];
            $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
            
            // Validar extensión del archivo
            $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($extension, $extensiones_permitidas)) {
                // Generar nombre único para evitar conflictos
                $nombre_imagen = uniqid() . '_' . time() . '.' . $extension;
                $ruta_destino = $directorio_imagenes . $nombre_imagen;
                
                // Mover archivo a la carpeta de imágenes
                if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
                    // Imagen subida correctamente
                } else {
                    header("Location: administracion.php?error=Error al subir la imagen");
                    exit();
                }
            } else {
                header("Location: administracion.php?error=Formato de imagen no permitido. Use JPG, PNG, GIF o WEBP");
                exit();
            }
        } else {
            header("Location: administracion.php?error=Debe subir una imagen para el plato");
            exit();
        }

        // Insertar plato en la base de datos con la ruta de la imagen
        $sql = "INSERT INTO plato (nombre, descripcion, precio, `menu_id menu`, imagen)
                VALUES ('$nombre', '$descripcion', $precio, $menu_id_menu, '$nombre_imagen')";
        
        if(mysqli_query($conexion, $sql)) {
            header("Location: administracion.php?mensaje=Plato agregado correctamente");
            exit();
        } else {
            // Si falla la inserción, eliminar la imagen subida
            if ($nombre_imagen && file_exists($directorio_imagenes . $nombre_imagen)) {
                unlink($directorio_imagenes . $nombre_imagen);
            }
            header("Location: administracion.php?error=Error al agregar plato: " . mysqli_error($conexion));
            exit();
        }
    }

    // Acción: Editar plato existente (solo nombre y precio)
    if ($accion == "editar_plato") {
        $id = $_POST['id'];
        $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
        $precio = $_POST['precio'];
        
        // Actualizar plato en la base de datos
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

    // Acción: Eliminar plato
    if ($accion == "eliminar_plato") {
        $id = $_POST['id'];
        
        // Primero obtener el nombre de la imagen para eliminarla del servidor
        $query_imagen = "SELECT imagen FROM plato WHERE plato_id = $id";
        $resultado = mysqli_query($conexion, $query_imagen);
        
        if ($resultado && $plato = mysqli_fetch_assoc($resultado)) {
            $nombre_imagen = $plato['imagen'];
            
            // Eliminar la imagen del servidor si existe
            if ($nombre_imagen && file_exists($directorio_imagenes . $nombre_imagen)) {
                unlink($directorio_imagenes . $nombre_imagen);
            }
        }
        
        // Luego eliminar el plato de la base de datos
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