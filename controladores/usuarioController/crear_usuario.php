<?php
header('Content-Type: application/json');
include_once("../../configuracion/conexion.php");

$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    if (!$email) {
        echo json_encode(['success' => false, 'message' => 'El correo es obligatorio']);
        exit;
    }

    // Verifica si el usuario ya existe
    $query = $conn->prepare("SELECT id_user FROM user WHERE em_user = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'El usuario ya existe']);
        $query->close();
        $conn->close();
        exit;
    }

    // Insertar nuevo usuario
    $query = $conn->prepare("INSERT INTO user (nom_user, ape_user, em_user, pas_user) VALUES (?, ?, ?, ?)");
    $query->bind_param("ssss", $nombre, $apellido, $email, $password);

    if ($query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear usuario']);
    }

    $query->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
