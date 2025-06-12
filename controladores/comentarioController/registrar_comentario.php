<?php
    // Recibir desde Android
    $id_usuario = $_POST['id_usuario'];
    $men_comentario = $_POST['men_comentario'];
    $est_comentario = $_POST['est_comentario'];
    // Llamar a la función
    require_once("../../modelos/comentario/comentario.php");
    $rpta = RegistrarComentario($id_usuario, $men_comentario, $est_comentario);
    // Responder a Android
    echo $rpta;
?>