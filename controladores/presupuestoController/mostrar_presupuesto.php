<?php
// Llamar a la función
$id_Usuario = $_GET['id_Usuario'];
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = MostrarPresupuestos($id_Usuario);
// Responder a Android
echo json_encode($rpta);
?>