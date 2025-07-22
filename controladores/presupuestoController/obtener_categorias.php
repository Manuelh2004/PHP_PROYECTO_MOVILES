<?php
// Mostrar errores (solo en desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Siempre antes de cualquier salida
header('Content-Type: application/json');

require_once("../../configuracion/conexion.php");
$con = conectar();

// Validar ID recibido
$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : 0;

if ($id_usuario <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "
SELECT c.id_categoria, c.nom_categoria
FROM categoria c
WHERE NOT EXISTS (
    SELECT 1 FROM presupuesto p 
    WHERE p.id_categoria = c.id_categoria 
    AND p.id_usuario = ? 
    AND p.est_presupuesto = 1
)
";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$categorias = [];
while ($fila = $resultado->fetch_assoc()) {
    $categorias[] = $fila;
}

echo json_encode($categorias);
?>
