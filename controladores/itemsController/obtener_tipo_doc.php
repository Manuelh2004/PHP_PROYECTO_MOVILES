<?php 
 require_once("../../modelos/tipo_documento/tipo_documento.php");
 $rpta = MostrarTipoDoc();
 echo json_encode($rpta);
?>