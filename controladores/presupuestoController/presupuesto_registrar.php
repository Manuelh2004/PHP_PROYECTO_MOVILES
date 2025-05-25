<?php
// Recibir desde Android
$montoP = $_GET['montoP'];
$id_usuario = $_GET['id_usuario'];
$categoriaP = $_GET['categoriaP'];
$fechaInicioP = $_GET['fechaInicioP'];
$fechaFinP = $_GET['fechaFinP'];
// Llamar a la función
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = RegistrarPresupuesto($montoP, $id_usuario, $categoriaP, $fechaInicioP, $fechaFinP);
// Responder a Android
echo $rpta;
?>