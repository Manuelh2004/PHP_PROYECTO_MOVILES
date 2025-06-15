<?php

require_once("../../configuracion/conexion.php");

$con = conectar();

$id_usuario = $_GET['id_usuario'];

$sql = "
SELECT c.nom_categoria AS categoria, SUM(p.pres_presupuesto) AS monto
FROM presupuesto p
JOIN categoria c ON p.id_categoria = c.id_categoria
WHERE p.id_usuario = '$id_usuario' AND p.est_presupuesto = 1
GROUP BY c.nom_categoria
";

$resultado = $con->query($sql);

$data = [];

while ($fila = $resultado->fetch_assoc()) {
    $data[] = [
        'categoria' => $fila['categoria'],
        'monto' => floatval($fila['monto'])
    ];
}

echo json_encode($data);
?>
