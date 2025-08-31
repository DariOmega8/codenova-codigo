<?php

session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = $_POST['gmail'];
    $pass = $_POST['contraseña'];

    $sql = "SELECT `id usuario`, nombre, contraseña FROM usuario WHERE gmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $gmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['contraseña'])) {
            $_SESSION['id_usuario'] = $row['id usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            header("Location: inicio.html"); 
            exit;
        } else {
            echo " Contraseña incorrecta";
        }
    } else {
        echo " Usuario no encontrado";
    }
}
?>