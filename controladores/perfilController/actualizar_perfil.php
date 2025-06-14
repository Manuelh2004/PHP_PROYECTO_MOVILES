<?php
    // Recibir desde Android
    $id_usuario = $_POST['id_usuario'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $documento = $_POST['documento'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];    // Llamar a la función
    require_once("../../modelos/usuario/usuario.php");
    $rpta = ActualizarUsuario($id_usuario, $nombres, $apellidos, $documento, $fecha_nacimiento, $telefono);
    // Responder a Android
    echo $rpta;
?>