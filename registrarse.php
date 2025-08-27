<?php

include 'conexion.php';
session_start();

if (isset($_POST['submit'])) {

    if (strlen($_POST['gmail']) < 2 || strlen($_POST['contraseña']) < 4) {
        echo "ingrese mas datos"; 
    } else {

        $gmail = $_POST['gmail'];
        $contraseña = $_POST['contraseña'];

        $sql = "INSERT INTO usuario (gmail, contraseña) VALUES ('$gmail', '$contraseña')";
        $resultado = mysqli_query($conexion, $sql);
        $id_usuario = mysqli_insert_id($conexion);

        $_SESSION['id_usuario'] = $id_usuario;

        if ($resultado) {
            header("Location: registrarse cliente.html");
            exit;
        } else {
            echo "Error en el registro: " . mysqli_error($conexion);
        }
    }
}
?>