<?php
require_once("../../configuracion/conexion.php");
$con = conectar();

// Validar si llega el parámetro
if (!isset($_POST['id_movimiento'])) {
    echo json_encode(["error" => "Falta el parámetro id_movimiento"]);
    exit;
}

$id_movimiento = $_POST['id_movimiento'];

// 1. Obtener los datos del movimiento antes de eliminar
$sql_select = "SELECT id_usuario, id_categoria, id_tipo_movimiento, mon_movimiento FROM movimiento WHERE id_movimiento = $id_movimiento";
$result = mysqli_query($con, $sql_select);

if (!$result || mysqli_num_rows($result) == 0) {
    echo json_encode(["error" => "Movimiento no encontrado"]);
    exit;
}

$movimiento = mysqli_fetch_assoc($result);
$id_usuario = $movimiento['id_usuario'];
$id_categoria = $movimiento['id_categoria'];
$id_tipo_movimiento = $movimiento['id_tipo_movimiento'];
$monto = $movimiento['mon_movimiento'];

// 2. Calcular operación sobre pres_presupuesto
// Ingreso (1): restar monto
// Egreso (2): sumar monto
$operacion = ($id_tipo_movimiento == 1) ? -$monto : $monto;

// 3. Actualizar pres_presupuesto en tabla presupuesto
$sql_update = "
    UPDATE presupuesto 
    SET pres_presupuesto = pres_presupuesto + $operacion
    WHERE id_usuario = $id_usuario 
      AND id_categoria = $id_categoria 
      AND est_presupuesto = 1
";

if (!mysqli_query($con, $sql_update)) {
    echo json_encode(["error" => "Error al actualizar el presupuesto"]);
    exit;
}

// 4. Eliminar el movimiento
$sql_delete = "DELETE FROM movimiento WHERE id_movimiento = $id_movimiento";

if (mysqli_query($con, $sql_delete)) {
    echo json_encode(["success" => "Movimiento eliminado y presupuesto actualizado"]);
} else {
    echo json_encode(["error" => "Error al eliminar el movimiento"]);
}

mysqli_close($con);
?>
