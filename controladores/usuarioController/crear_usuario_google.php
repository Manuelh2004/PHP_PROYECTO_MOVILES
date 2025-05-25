<?php
header('Content-Type: application/json');
include_once("../../configuracion/conexion.php");

$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos los datos del usuario
    $nombres = $_POST['nombres'] ?? null;
    $apellidos = $_POST['apellidos'] ?? null;
    $email = $_POST['email'] ?? null;
    $uid_firebase = $_POST['uid_firebase'] ?? null; // El UID de Firebase debe ser enviado por el cliente

    // Verificar si los datos esenciales están presentes
    if (!$email || !$nombres || !$apellidos || !$uid_firebase) {
        echo json_encode(['success' => false, 'message' => 'Los campos obligatorios (nombres, apellidos, email y uid_firebase) son requeridos.']);
        exit;
    }

    // Verificar si el usuario ya existe en la base de datos
    $query = $conn->prepare("SELECT id_usuario FROM usuario WHERE em_usuario = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
        $query->close();
        $conn->close();
        exit;
    }

    // Asignar valores por defecto para los campos que no se reciben (por ejemplo, est_usuario, num_usuario, id_pais, id_genero, id_tipo_documento)
    $id_pais = 1;  // Puedes cambiar esto según tus necesidades o recibirlo como parámetro
    $id_genero = 1;  // Similar para el género
    $id_tipo_doc = 1;  // Y el tipo de documento
    $num_usuario = ''; // Si no tienes un valor para num_usuario, lo dejamos vacío
    $est_usuario = 1; // Activo

    // Insertar nuevo usuario en la base de datos
    $query = $conn->prepare("INSERT INTO usuario (nom_usuario, ape_usuario, em_usuario, uid_firebase, id_pais, id_genero, id_tipo_documento, num_usuario, est_usuario) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("ssssiiiss", $nombres, $apellidos, $email, $uid_firebase, $id_pais, $id_genero, $id_tipo_doc, $num_usuario, $est_usuario);

    if ($query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario creado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear usuario: ' . $query->error]);
    }

    $query->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
