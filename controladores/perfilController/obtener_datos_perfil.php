<?php
    // Recibir desde Android
    $id_usuario  = $_POST['id_usuario'];
    // Llamar a la función
    require_once("../../modelos/usuario/usuario.php");
    $rpta = ObtenerDatosPerfilYListas($id_usuario);
    // Responder a Android
    echo $rpta;
?>