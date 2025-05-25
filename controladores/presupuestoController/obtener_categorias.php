<?php

require_once("../../configuracion/conexion.php");

$con = conectar();

$sql = "SELECT id_categoria, nom_categoria FROM categoria";
$resultado = $con->query($sql);

$categorias = array();
while ($fila = $resultado->fetch_assoc()) {
    $categorias[] = $fila;
}

echo json_encode($categorias);
?>
