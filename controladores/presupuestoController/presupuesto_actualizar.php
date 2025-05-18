<?php
// Recibir desde Android
$idPresupuesto = $_GET['idPresupuesto'];
$montoP = $_GET['montoP'];
$idUsuario = $_GET['idUsuario'];
$categoriaP = $_GET['categoriaP'];
$fechaInicioP = $_GET['fechaInicioP'];
$fechaFinP = $_GET['fechaFinP'];
// Llamar a la función
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = ActualizarPresupuesto($idPresupuesto, $montoP, $idUsuario, $categoriaP, $fechaInicioP, $fechaFinP);
// Responder a Android
echo $rpta;
?>