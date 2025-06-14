<?php
 require_once("../../modelos/usuario/usuario.php");
 $rpta = ListarUsuario();
 echo json_encode($rpta);
?>