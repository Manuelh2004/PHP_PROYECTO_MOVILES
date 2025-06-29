<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Permitir acceso desde cualquier origen
header("Access-Control-Allow-Methods: GET");

// Crear conexión
require_once("../../configuracion/conexion.php");

$con = conectar();

// Verificar conexión
if ($con->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error al conectar a la base de datos"]);
    exit();
}

// Consulta para obtener todas las categorías
$sql = "SELECT id_tipo_documento, nom_tipo_documento FROM tipo_documento";
$resultado = $con->query($sql);

$categorias = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $categorias[] = $fila; // cada fila tiene 'id' y 'nombre'
    }
}

// Cerrar conexión
$con->close();

// Devolver en formato JSON
echo json_encode($categorias);
?>