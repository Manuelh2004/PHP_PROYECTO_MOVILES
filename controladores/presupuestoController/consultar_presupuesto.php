<?php
// Llamar a la función
$idPresupuesto = $_GET['idPresupuesto'];
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = ConsultarPresupuestos($idPresupuesto);
// Responder a Android
echo json_encode($rpta);
?>