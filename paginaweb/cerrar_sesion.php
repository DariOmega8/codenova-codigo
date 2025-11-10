<?php
/**
 * archivo: cerrar_sesion.php
 * Descripción: Maneja el cierre de sesión del usuario
 * Función: Destruye todas las variables de sesión y redirige al inicio
 */

// Iniciar la sesión para poder acceder a las variables de sesión existentes
session_start();

// Limpiar todas las variables de sesión
// session_unset() elimina todas las variables registradas en la sesión actual
// pero mantiene la sesión activa
session_unset(); 

// Destruir completamente la sesión
// session_destroy() elimina toda la información asociada con la sesión actual
// Esto incluye:
// - Todas las variables de sesión ($_SESSION)
// - El archivo de sesión en el servidor
// - La cookie de sesión en el navegador del cliente
session_destroy(); 

// Redirigir al usuario a la página de inicio
// Después de cerrar sesión, es buena práctica redirigir al usuario
// para evitar que permanezca en páginas que requieran autenticación
header("Location: inicio.php");  

// Asegurarse de que el script se detenga después de la redirección
// exit termina la ejecución del script inmediatamente
// Esto previene que se ejecute código adicional después de la redirección
exit;

?>