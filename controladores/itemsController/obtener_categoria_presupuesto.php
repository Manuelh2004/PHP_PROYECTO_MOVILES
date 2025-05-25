<?php 
 require_once("../../modelos/categoria/categoria.php");
 $rpta = MostrarCategoriaConPresupuesto();
 echo json_encode($rpta);
?>