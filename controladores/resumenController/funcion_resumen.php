<?php

require_once("../../configuracion/conexion.php");

$con = conectar();

if (!isset($_GET['id_usuario'])) {
    echo json_encode(["error" => "Falta el parámetro id_usuario"]);
    exit;
}

$id_usuario = $_GET['id_usuario'];

$sql = "
SELECT 
    IFNULL(SUM(CASE WHEN id_tipo_movimiento = 1 THEN mon_movimiento ELSE 0 END), 0) AS total_ingresos,
    IFNULL(SUM(CASE WHEN id_tipo_movimiento = 2 THEN mon_movimiento ELSE 0 END), 0) AS total_egresos
FROM movimiento
WHERE id_usuario = '$id_usuario'
";

$resultado = $con->query($sql);

$resumen = [];

if ($fila = $resultado->fetch_assoc()) {
    $ingresos = floatval($fila['total_ingresos']);
    $egresos = floatval($fila['total_egresos']);
    $saldo = $ingresos - $egresos;

    $resumen[] = array(
        'total_ingresos' => $ingresos,
        'total_egresos' => $egresos,
        'saldo_disponible' => $saldo
    );
}

echo json_encode($resumen);
?>
