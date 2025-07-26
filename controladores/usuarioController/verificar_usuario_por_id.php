<?php
include_once("../../configuracion/conexion.php");

$conn = conectar();

$id_usuario = $_GET['id_usuario'];

$query = "SELECT id_usuario FROM usuario WHERE id_usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$response = array();
$response["existe"] = ($result->num_rows > 0);

echo json_encode($response);
?>
