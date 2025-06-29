<?php
header('Content-Type: application/json');

require_once("../../configuracion/conexion.php");

$con = conectar();

// Recibir los datos enviados desde Android
$nombres = $_POST['nombres'] ?? '';
$apellidos = $_POST['apellidos'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$documento = $_POST['documento'] ?? '';
$fechaNa = $_POST['fechaNa'] ?? '';
$idGenero = isset($_POST['idGenero']) ? intval($_POST['idGenero']) : 0;
$idTipoDoc = isset($_POST['idTipoDoc']) ? intval($_POST['idTipoDoc']) : 0;
$email = $_POST['email'] ?? '';
$uid_firebase = $_POST['uid_firebase'] ?? '';

// Registrar el email en el log antes de cualquier validación
error_log('Email recibido antes de validar: ' . $email);

// Validaciones adicionales para evitar errores con el email
if ($email === '0' || empty($email)) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error: El email no puede ser 0 ni estar vacío'
    ]);
    exit;
}

// Verificar si el email tiene el formato correcto
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'El email proporcionado no es válido'
    ]);
    exit;
}

// Verificar si el email ya existe en la base de datos
$sql_check = "SELECT em_usuario FROM usuario WHERE em_usuario = ?";
$stmt_check = $con->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result(); // Asegura que los datos están disponibles

if ($stmt_check->num_rows > 0) {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'El email ya está registrado'
    ]);
    $stmt_check->close();
    exit;
}

$stmt_check->close();

// **Consulta directa en lugar de bind_param**
// **Consulta directa con el valor de $documento**
$sql_debug = "INSERT INTO usuario (nom_usuario, ape_usuario, tel_usuario, fna_usuario, id_genero, id_tipo_documento, em_usuario, est_usuario, num_usuario, uid_firebase) 
              VALUES ('$nombres', '$apellidos', '$telefono', '$fechaNa', '$idGenero', '$idTipoDoc', '$email', 1, '$documento', '$uid_firebase')";

// Registrar el email antes de la inserción en la base de datos
error_log('Consulta ejecutada: ' . $sql_debug);

if ($con->query($sql_debug) === TRUE) {
    echo json_encode([
        'exito' => true,
        'mensaje' => 'Usuario registrado con éxito sin bind_param()'
    ]);
} else {
    echo json_encode([
        'exito' => false,
        'mensaje' => 'Error al registrar usuario: ' . $con->error
    ]);
}

// Cerrar la conexión
$con->close();
?>