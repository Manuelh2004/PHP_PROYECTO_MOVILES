<?php
header('Content-Type: application/json');

require_once("../../configuracion/conexion.php");

$con = conectar();

// Recibir con los nombres que envía Android
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$documento = $_POST['documento'] ?? '';
$fechaNa = $_POST['fechaNa'] ?? '';
$idGenero = isset($_POST['idGenero']) ? intval($_POST['idGenero']) : 0;
$idTipoDoc = isset($_POST['idTipoDoc']) ? intval($_POST['idTipoDoc']) : 0;
$email = $_POST['email'] ?? '';
$uid_firebase = $_POST['uid_firebase'] ?? '';

// Validación simple
if (empty($nombres) || empty($apellidos) || empty($telefono) || empty($documento) || empty($fechaNa) 
    || $idGenero === 0 || $idTipoDoc === 0 || empty($email) || empty($uid_firebase)) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Faltan datos obligatorios'
    ]);
    exit;
}

$est_usuario = 1; 
$num_usuario = $documento;
$id_tipo_usuario = 2; // Siempre será 2

// SQL modificado para incluir id_tipo_usuario
$sql = "INSERT INTO usuario 
    (nom_usuario, ape_usuario, tel_usuario, fna_usuario, id_genero, id_tipo_documento, em_usuario, est_usuario, num_usuario, uid_firebase, id_tipo_usuario) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $con->prepare($sql);

if (!$stmt) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error en la preparación de la consulta: ' . $con->error
    ]);
    exit;
}

// Ajustar bind_param para incluir id_tipo_usuario
$stmt->bind_param(
    "ssssiiissss",  // Cambié a 11 parámetros para incluir id_tipo_usuario
    $nombres,       // nom_usuario
    $apellidos,     // ape_usuario
    $telefono,      // tel_usuario
    $fechaNa,       // fna_usuario
    $idGenero,      // id_genero
    $idTipoDoc,     // id_tipo_documento
    $email,         // em_usuario
    $est_usuario,   // est_usuario
    $num_usuario,   // num_usuario
    $uid_firebase,  // uid_firebase
    $id_tipo_usuario // id_tipo_usuario, siempre será 2
);

if ($stmt->execute()) {
    echo json_encode([
        'exito' => true,
        'mensaje' => 'Usuario registrado con éxito'
    ]);
} else {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error al registrar usuario: ' . $stmt->error
    ]);
}

$stmt->close();
$con->close();
?>
