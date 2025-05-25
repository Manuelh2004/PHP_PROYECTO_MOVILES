<?php
// Recibir datos desde Android vía POST
$id_tipo_movimiento = $_POST['id_tipo_movimiento'];
$id_usuario = $_POST['id_usuario'];
$id_categoria = $_POST['id_categoria'];
$mon_movimiento = $_POST['mon_movimiento'];
$fech_movimiento = $_POST['fech_movimiento'];
$des_movimiento = $_POST['des_movimiento'];
$est_movimiento = $_POST['est_movimiento'];

// Incluir el modelo
require_once("../../modelos/movimiento/movimiento.php");

// Llamar función para insertar movimiento
$rpta = RegistrarMovimiento($id_tipo_movimiento, $id_usuario, $id_categoria, $mon_movimiento, $fech_movimiento, $des_movimiento, $est_movimiento);

// Devolver respuesta a Android
echo $rpta;
?>
