<?php

require_once("../../configuracion/conexion.php");

$con = conectar();

$id_usuario = $_GET['id_usuario'];

$sql = "
SELECT 
    SUM(pres_presupuesto) AS total_ingresos,
    SUM(pres_act_presupuesto) AS saldo_disponible,
    SUM(pres_presupuesto - pres_act_presupuesto) AS total_egresos
FROM presupuesto
WHERE id_usuario = '$id_usuario' AND est_presupuesto = 1
";

$resultado = $con->query($sql);

$resumen = array();

if ($fila = $resultado->fetch_assoc()) {
    $resumen[] = array(
        'total_ingresos' => floatval($fila['total_ingresos']),
        'total_egresos' => floatval($fila['total_egresos']),
        'saldo_disponible' => floatval($fila['saldo_disponible'])
    );
}

echo json_encode($resumen);
?>
