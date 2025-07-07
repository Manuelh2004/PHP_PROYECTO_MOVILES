<?php
require_once("../../configuracion/conexion.php");
$con = conectar();

$id_mov = $_POST['id_movimiento'] ?? null;
$id_usr = $_POST['id_usuario'] ?? null;
$tipo_nuevo = $_POST['id_tipo_movimiento'] ?? null;
$cat_nuevo = $_POST['id_categoria'] ?? null;
$mon_nuevo = floatval($_POST['mon_movimiento'] ?? 0);
$fecha = $_POST['fech_movimiento'] ?? null;
$desc = $_POST['des_movimiento'] ?? '';
$est = $_POST['est_movimiento'] ?? null;

if (!$id_mov || !$id_usr || !$tipo_nuevo || !$cat_nuevo || $mon_nuevo === null || !$fecha || !$desc || $est === null) {
    echo "Faltan parámetros"; exit;
}

// 1. Obtener datos anteriores
$sql_old = "SELECT mon_movimiento, id_tipo_movimiento, id_categoria 
            FROM movimiento 
            WHERE id_movimiento = ? AND id_usuario = ?";
$stmt_old = mysqli_prepare($con, $sql_old);
mysqli_stmt_bind_param($stmt_old, "ii", $id_mov, $id_usr);
mysqli_stmt_execute($stmt_old);
$result = mysqli_stmt_get_result($stmt_old);
if (!$old = mysqli_fetch_assoc($result)) {
    echo "Movimiento no encontrado"; exit;
}

$monto_ant = floatval($old['mon_movimiento']);
$tipo_ant = intval($old['id_tipo_movimiento']);
$cat_ant = intval($old['id_categoria']);

// 2. Calcular el ajuste al presupuesto
$ajuste_presupuesto = 0;

// Si se cambió tipo o categoría, revertimos el anterior y aplicamos el nuevo
if ($tipo_ant != $tipo_nuevo || $cat_ant != $cat_nuevo) {
    $ajuste_anterior = ($tipo_ant == 1) ? -$monto_ant : $monto_ant;
    $ajuste_nuevo = ($tipo_nuevo == 1) ? $mon_nuevo : -$mon_nuevo;

    // Revertir en categoría anterior
    $sql_revertir = "UPDATE presupuesto SET pres_presupuesto = pres_presupuesto + ? 
                     WHERE id_usuario = ? AND id_categoria = ? AND est_presupuesto = 1";
    $stmt_rev = mysqli_prepare($con, $sql_revertir);
    mysqli_stmt_bind_param($stmt_rev, "dii", $ajuste_anterior, $id_usr, $cat_ant);
    mysqli_stmt_execute($stmt_rev);

    // Aplicar nuevo en categoría nueva
    $stmt_nuevo = mysqli_prepare($con, $sql_revertir); // misma SQL
    mysqli_stmt_bind_param($stmt_nuevo, "dii", $ajuste_nuevo, $id_usr, $cat_nuevo);
    mysqli_stmt_execute($stmt_nuevo);

} else {
    // Si no cambió tipo ni categoría, solo se ajusta la diferencia
    $diferencia = $mon_nuevo - $monto_ant;
    $ajuste = ($tipo_nuevo == 1) ? $diferencia : -$diferencia;

    $sql_ajuste = "UPDATE presupuesto SET pres_presupuesto = pres_presupuesto + ? 
                   WHERE id_usuario = ? AND id_categoria = ? AND est_presupuesto = 1";
    $stmt_ajuste = mysqli_prepare($con, $sql_ajuste);
    mysqli_stmt_bind_param($stmt_ajuste, "dii", $ajuste, $id_usr, $cat_nuevo);
    mysqli_stmt_execute($stmt_ajuste);
}

// 3. Actualizar el movimiento
$sql_update = "UPDATE movimiento 
               SET id_tipo_movimiento = ?, id_categoria = ?, mon_movimiento = ?, 
                   fech_movimiento = ?, des_movimiento = ?, est_movimiento = ? 
               WHERE id_movimiento = ? AND id_usuario = ?";
$stmt_upd = mysqli_prepare($con, $sql_update);
mysqli_stmt_bind_param($stmt_upd, "iidssiii", $tipo_nuevo, $cat_nuevo, $mon_nuevo, $fecha, $desc, $est, $id_mov, $id_usr);
mysqli_stmt_execute($stmt_upd);

echo "success";
mysqli_close($con);
?>
