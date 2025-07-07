<?php
header('Content-Type: application/json');
include_once("../../configuracion/conexion.php");

$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nom_usuario'] ?? '';
    $email = $_POST['em_usuario'] ?? '';
    $uid_firebase = $_POST['uid_firebase'] ?? '';

    if (empty($nombre) || empty($email) || empty($uid_firebase)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Verificar si ya existe por uid_firebase
    $queryCheck = "SELECT id_usuario FROM usuario WHERE uid_firebase = ?";
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bind_param("s", $uid_firebase);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['success' => true, 'id_usuario' => $row['id_usuario']]);
        exit;
    }

    // Valores por defecto
    $apellido = "";
    $fecha_nac = "";
    $telefono = "";
    $num_documento = "";
    $contrasena = "google_user";
    $fecha_registro = date('Y-m-d H:i:s');
    $estado = 1;

    // Asignaciones de FK: deberías asegurarte que estos valores existen
    $id_genero = 1; // Por ejemplo: 1 = "No especificado"
    $id_tipo_documento = 1; // Por ejemplo: 1 = "DNI"
    $id_tipo_usuario = 2; // Por ejemplo: 2 = "Usuario estándar"

    $stmt = $conn->prepare("INSERT INTO usuario 
        (id_genero, id_tipo_documento, id_tipo_usuario, nom_usuario, ape_usuario, fna_usuario, tel_usuario, num_usuario, em_usuario, pas_usuario, fre_usuario, uid_firebase, est_usuario) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("iiisssssssssi", 
        $id_genero, 
        $id_tipo_documento, 
        $id_tipo_usuario, 
        $nombre, 
        $apellido, 
        $fecha_nac, 
        $telefono, 
        $num_documento, 
        $email, 
        $contrasena, 
        $fecha_registro, 
        $uid_firebase, 
        $estado
    );

    if ($stmt->execute()) {
        $nuevoId = $stmt->insert_id;
        echo json_encode(['success' => true, 'id_usuario' => $nuevoId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar usuario']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
