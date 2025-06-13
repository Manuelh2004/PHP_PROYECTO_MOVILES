<?php

require_once("../../configuracion/conexion.php");

$con = conectar();

$id_usuario = $_GET['id_usuario'];

$sql = "
SELECT c.id_categoria, c.nom_categoria
FROM categoria c
JOIN presupuesto p ON c.id_categoria = p.id_categoria
WHERE p.id_usuario = '$id_usuario' AND P.pres_presupuesto > 0
GROUP BY c.id_categoria, c.nom_categoria";

$resultado = $con->query($sql);

$categorias = array();
while ($fila = $resultado->fetch_assoc()) {
    $categorias[] = $fila;
}

echo json_encode($categorias);
?>
