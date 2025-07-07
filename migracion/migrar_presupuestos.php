<?php
header('Content-Type: application/json');
include_once("../../configuracion/conexion.php");
$conn = conectar();

date_default_timezone_set('America/Lima'); // Ajusta a tu zona horaria
$hoy = date('Y-m-d');

// 1. Obtener presupuestos cuya fecha final ya pasó
$sql = "SELECT id_presupuesto FROM presupuesto WHERE ffin_presupuesto <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hoy);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $insert_stmt = $conn->prepare("INSERT INTO historial_presupuesto (id_presupuesto, fre_historial_presupuesto) VALUES (?, ?)");
    $delete_stmt = $conn->prepare("DELETE FROM presupuesto WHERE id_presupuesto = ?");

    while ($row = $result->fetch_assoc()) {
        $id_presupuesto = $row['id_presupuesto'];

        // 2. Insertar en historial
        $insert_stmt->bind_param("is", $id_presupuesto, $hoy);
        $insert_stmt->execute();

        // 3. Eliminar del presupuesto actual
        $delete_stmt->bind_param("i", $id_presupuesto);
        $delete_stmt->execute();
    }

    echo json_encode(["status" => "success", "mensaje" => "Presupuestos vencidos migrados correctamente."]);
} else {
    echo json_encode(["status" => "ok", "mensaje" => "No hay presupuestos vencidos por migrar."]);
}
?>
