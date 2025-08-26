<?php

include 'conexion.php';

if (isset($_POST['submit'])) {

    if (strlen($_POST['gmail']) < 2 || strlen($_POST['contraseña']) < 4) {
        echo "ingrese mas datos"; 
    } else {

        $gmail = $_POST['gmail'];
        $contraseña = $_POST['contraseña'];

        $sql = "INSERT INTO usuario (gmail, contraseña) VALUES ('$gmail', '$contraseña')";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado) {
            header("Location: inicio.html");
            exit;
        } else {
            echo "Error en el registro: " . mysqli_error($conexion);
        }
    }
}
?>