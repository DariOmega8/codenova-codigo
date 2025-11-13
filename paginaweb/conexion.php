<?php
/**
 * archivo: conexion.php
 * Descripción: Maneja la conexión a la base de datos MySQL
 * Propósito: Establecer y verificar la conexión con la base de datos del restaurante
 * Uso: Incluir este archivo en cualquier página que necesite acceso a la base de datos
 */

// ============================
// CONFIGURACIÓN DE LA BASE DE DATOS
// ============================

// Servidor de la base de datos (generalmente 'localhost' en entornos de desarrollo)
$server = "localhost";

// Usuario de la base de datos ('root' es el usuario por defecto en XAMPP/WAMP)
$user = "root";

// Contraseña de la base de datos (vacía por defecto en XAMPP/WAMP)
$password = "";

// Nombre de la base de datos que se va a utilizar
$db = "restaurante";

// ============================
// ESTABLECER CONEXIÓN
// ============================

// Crear una nueva instancia de mysqli para conectarse a la base de datos
// mysqli es la extensión mejorada de MySQL para PHP
$conexion = new mysqli($server, $user, $password, $db);

// ============================
// VERIFICAR CONEXIÓN
// ============================

// Verificar si hubo un error en la conexión
// connect_error contiene la descripción del error si la conexión falla
if ($conexion->connect_error) {
    // Si hay error, terminar el script y mostrar mensaje de error
    // die() detiene la ejecución del script y muestra un mensaje
    die("Conexion fallida" . $conexion->connect_error);
}

// Si no hay errores, la conexión se estableció correctamente
// La variable $conexion ahora puede usarse para ejecutar consultas en otras páginas

?>