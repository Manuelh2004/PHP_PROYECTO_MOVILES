<?php
// Recibir desde Android
$email = $_GET['em_usuario'];
$password = $_GET['pas_usuario'];
// Llamar a la función
require_once("funcion_usuario.php");
$rpta = ValidarUsuario($email, $password);
// Responder a Android
echo json_encode($rpta, JSON_UNESCAPED_UNICODE);
?>