<?php
    // Recibir desde Android
    $id_usuario  = $_GET['id_usuario'];
    // Llamar a la función
    require_once("../../modelos/usuario/usuario.php");
    $rpta = ObtenerNombre($id_usuario);
    // Responder a Android
    echo $rpta;
?>