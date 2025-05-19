<?php 
 require_once("../../modelos/pais/pais.php");
 $rpta = MostrarPais();
 echo json_encode($rpta);
?>