<?php
    // Recibir desde Android
    $id_usuario = $_POST['id_usuario'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $documento = $_POST['documento'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $telefono = $_POST['telefono'];    // Llamar a la función
    $id_genero = $_POST['id_genero'];  // Recibir el ID de género
    $id_tipo_documento = $_POST['id_tipo_documento'];  // Recibir el ID de tipo de documento
    // Llamar a la función para actualizar los datos
    require_once("../../modelos/usuario/usuario.php");
    $rpta = ActualizarUsuario($id_usuario, $nombres, $apellidos, $documento, $fecha_nacimiento, $telefono, $id_genero, $id_tipo_documento);
    
    echo $rpta;
?>