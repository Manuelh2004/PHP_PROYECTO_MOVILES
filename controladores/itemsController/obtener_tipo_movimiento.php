<?php 
 require_once("../../modelos/tipo_movimiento/tipo_movimiento.php");
 $rpta = MostrarTipoMovimiento();
 echo json_encode($rpta);
?>