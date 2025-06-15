<?php
    require_once("../../modelos/comentario/comentario.php");
    $rpta = ListarComentario();
    echo json_encode($rpta);
?>