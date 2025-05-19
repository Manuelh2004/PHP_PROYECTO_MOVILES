<?php 
 require_once("../../modelos/genero/genero.php");
 $rpta = MostrarGenero();
 echo json_encode($rpta);
?>