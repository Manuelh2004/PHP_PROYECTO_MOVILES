<?php 
 require_once("../../modelos/movimiento/movimiento.php");
 $rpta = MostrarMovimiento();
 echo json_encode($rpta);
?>