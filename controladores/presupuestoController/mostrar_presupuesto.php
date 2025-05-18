<?php
// Llamar a la función
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = MostrarPresupuestos();
// Responder a Android
echo json_encode($rpta);
?>