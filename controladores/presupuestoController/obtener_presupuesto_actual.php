<?php
header('Content-Type: application/json');
include_once("../../configuracion/conexion.php");

$conn = conectar();

$id_usuario = $_POST['id_usuario'] ?? null;
$id_categoria = $_POST['id_categoria'] ?? null;

if ($id_usuario && $id_categoria) {
    $sql = "SELECT pres_presupuesto 
            FROM presupuesto 
            WHERE id_usuario = ? 
              AND id_categoria = ?
              AND est_presupuesto = 1
            ORDER BY id_presupuesto DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "presupuesto_actual" => $row["pres_presupuesto"]
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "mensaje" => "No se encontró presupuesto"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "Faltan parámetros"
    ]);
}
?>