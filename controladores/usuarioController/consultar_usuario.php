<?php
// Recibir desde Android
$id_usuario = $_GET['id_usuario'];
// Llamar a la función
require_once("../../modelos/usuario/funcion_usuario.php");
$rpta = ValidarUsuario($id_usuario);
// Responder a Android
echo json_encode($rpta, JSON_UNESCAPED_UNICODE);
?>