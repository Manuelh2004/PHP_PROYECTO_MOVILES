<?php
header('Content-Type: application/json');
include_once("../configuracion/conexion.php");
$conn = conectar();

date_default_timezone_set('America/Lima');
$hoy = date('Y-m-d');

// 1. Obtener presupuestos cuya fecha final ya pasó
$sql = "SELECT id_presupuesto FROM presupuesto WHERE ffin_presupuesto <= ? AND est_presupuesto = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hoy);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $insert_stmt = $conn->prepare("INSERT INTO historial_presupuesto (id_presupuesto, fre_historial_presupuesto) VALUES (?, ?)");
    $update_stmt = $conn->prepare("UPDATE presupuesto SET est_presupuesto = 0 WHERE id_presupuesto = ?");

    while ($row = $result->fetch_assoc()) {
        $id_presupuesto = $row['id_presupuesto'];

        // Insertar en historial
        $insert_stmt->bind_param("is", $id_presupuesto, $hoy);
        $insert_stmt->execute();

        // Actualizar estado a inactivo
        $update_stmt->bind_param("i", $id_presupuesto);
        $update_stmt->execute();
    }

    echo json_encode(["status" => "success", "mensaje" => "Presupuestos vencidos migrados correctamente y marcados como inactivos."]);
} else {
    echo json_encode(["status" => "ok", "mensaje" => "No hay presupuestos vencidos por migrar."]);
}
?>
