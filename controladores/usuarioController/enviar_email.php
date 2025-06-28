<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

file_put_contents("log_correo.txt", json_encode($_POST) . PHP_EOL, FILE_APPEND);

$email = $_POST['email'] ?? '';
$nombre = $_POST['nombre'] ?? '';

if (empty($email) || empty($nombre)) {
    echo json_encode(['success' => false, 'error' => 'Faltan datos']);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sanfranciscoalbergue90@gmail.com';
    $mail->Password = 'utav qvlt gefk vucg'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('sanfranciscoalbergue90@gmail.com', 'Pockit');
    $mail->addAddress($email, $nombre);
    $mail->Subject = 'Saludos desde la App';
    $mail->Body    = "Hola $nombre,\n\nGracias por usar nuestra aplicación.\n\n¡Te mandamos un cordial saludo!";

    $mail->send();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $mail->ErrorInfo]);
}
