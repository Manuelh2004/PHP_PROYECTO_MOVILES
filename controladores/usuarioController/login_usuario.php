<?php
header('Content-Type: application/json');
require_once ("../../configuracion/conexion.php");

$conn = conectar();

if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión a la base de datos"
    ]);
    exit();
}

// Recibe email por POST
$email = isset($_POST['email']) ? $_POST['email'] : '';

if (empty($email)) {
    echo json_encode([
        "success" => false,
        "message" => "Email no proporcionado"
    ]);
    exit();
}

// Consulta preparada segura, buscando por em_usuario
$stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE em_usuario = ?");
if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la preparación de la consulta"
    ]);
    exit();
}

$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id_usuario);
    $stmt->fetch();

    echo json_encode([
        "success" => true,
        "id_usuario" => $id_usuario,
        "message" => "Usuario encontrado"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no encontrado"
    ]);
}

$stmt->close();
$conn->close();
?>
