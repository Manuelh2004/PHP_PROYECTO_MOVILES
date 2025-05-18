<?php
// Llamar a la función
$idPresupuesto = $_GET['idPresupuesto'];
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = EliminarPresupuestos($idPresupuesto);
// Responder a Android
echo $rpta;
?>