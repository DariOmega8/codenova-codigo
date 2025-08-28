<?php

include 'conexion.php';
session_start();

if (isset($_POST['submit'])) {

    if (empty($_POST['gmail'])|| empty($_POST['contraseña']) ) {
        echo "ingrese los datos"; 
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