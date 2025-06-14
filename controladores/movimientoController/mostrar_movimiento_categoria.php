<?php 
 require_once("../../modelos/movimiento/movimiento.php");
 $rpta = ObtenerCategorias();
 echo json_encode($rpta);
?>