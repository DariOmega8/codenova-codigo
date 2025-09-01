<?php

session_start();
session_unset(); // limpia variables de sesión
session_destroy(); // destruye la sesión
header("Location: inicio.php"); // vuelve al inicio
exit;


?>