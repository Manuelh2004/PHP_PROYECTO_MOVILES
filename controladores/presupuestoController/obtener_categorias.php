<?php

require_once("../../configuracion/conexion.php");

$con = conectar();

$usuario = $_GET['id_usuario']; // o como lo pases en la solicitud

$stmt = $con->prepare("SELECT id_categoria, nom_categoria FROM categoria WHERE id_categoria NOT IN (SELECT id_categoria FROM presupuesto WHERE id_usuario = ?)");
$stmt->bind_param("i", $usuario);
$stmt->execute();

$resultado = $stmt->get_result();

$categorias = array();
while ($fila = $resultado->fetch_assoc()) {
    $categorias[] = $fila;
}

echo json_encode($categorias);
?>
