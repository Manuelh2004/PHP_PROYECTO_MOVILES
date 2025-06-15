<?php
require_once("../../configuracion/conexion.php");

$con = conectar();

// Obtener el ID del usuario desde la URL
$id_usuario = $_GET['id_usuario'] ?? null;

if (!$id_usuario) {
    echo json_encode(["error" => "ID de usuario no proporcionado"]);
    exit;
}

// Consulta que suma egresos por categoría del usuario
$sql = "
SELECT c.nom_categoria AS categoria, SUM(p.pres_presupuesto) AS monto
FROM presupuesto p
JOIN categoria c ON p.id_categoria = c.id_categoria
WHERE p.id_usuario = '$id_usuario' AND p.pres_presupuesto > 0 AND p.est_presupuesto = 1
GROUP BY c.id_categoria, c.nom_categoria
";

$resultado = $con->query($sql);

$egresos = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $egresos[] = [
            "categoria" => $fila["categoria"],
            "monto" => (float)$fila["monto"]
        ];
    }
    echo json_encode($egresos);
} else {
    echo json_encode(["error" => "Error en la consulta: " . $con->error]);
}

$con->close();
?>
