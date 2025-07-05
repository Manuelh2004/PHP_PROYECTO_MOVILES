<?php
// Recibir desde Android
$idPresupuesto = $_GET['idPresupuesto'];
$montoP = $_GET['montoP'];
$idUsuario = $_GET['idUsuario'];
$categoriaP = $_GET['categoriaP'];
$montoActualp = $_GET['montoActualp'];
$fechaInicioP = $_GET['fechaInicioP'];
$fechaFinP = $_GET['fechaFinP'];

// Llamar a la función
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = ActualizarPresupuesto($idPresupuesto, $montoP, $montoActualp, $idUsuario, $categoriaP, $fechaInicioP, $fechaFinP);

// Responder a Android como JSON
header('Content-Type: application/json');  // Establecer el tipo de contenido como JSON
echo json_encode(array("mensaje" => $rpta));  // Convertir el mensaje a JSON
?>
