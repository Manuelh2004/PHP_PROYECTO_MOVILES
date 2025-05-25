<?php
header('Content-Type: application/json');
include '../../configuracion/conexion.php';

// Importar clases PHPMailer (ajusta la ruta si no usas autoload)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';  // Ajusta la ruta según tu estructura

$conexion = conectar();

function generarCodigo($longitud = 6) {
    return substr(str_shuffle("0123456789"), 0, $longitud);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';

    if (!$email) {
        echo json_encode(["exito" => false, "mensaje" => "Faltan datos requeridos."]);
        exit;
    }

    // Preparar statement para verificar usuario
    $stmt = $conexion->prepare("SELECT esver_usuario FROM usuario WHERE em_usuario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($es_verificado);

    if ($stmt->fetch()) {
        $stmt->close();

        if ($es_verificado == 1) {
            echo json_encode(["exito" => false, "mensaje" => "El email ya está verificado."]);
            $conexion->close();
            exit;
        }

        // Generar nuevo código
        $nuevoCodigo = generarCodigo(6);

        // Actualizar código en BD
        $stmt2 = $conexion->prepare("UPDATE usuario SET cod_usuario = ? WHERE em_usuario = ?");
        $stmt2->bind_param("ss", $nuevoCodigo, $email);

        if ($stmt2->execute()) {
            $stmt2->close();
            $conexion->close();

            // Enviar correo con PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Cambia por tu SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'tuemail@gmail.com';  // Cambia por tu email
                $mail->Password = 'tu_contraseña_o_app_password';  // Cambia por tu password o app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Destinatarios
                $mail->setFrom('tuemail@gmail.com', 'Tu Nombre');
                $mail->addAddress($email);

                // Contenido
                $mail->isHTML(false);
                $mail->Subject = 'Nuevo código de verificación';
                $mail->Body    = "Tu nuevo código de verificación es: $nuevoCodigo";

                $mail->send();

                echo json_encode(["exito" => true, "mensaje" => "Nuevo código enviado al correo."]);

            } catch (Exception $e) {
                echo json_encode(["exito" => false, "mensaje" => "No se pudo enviar el correo. Error: " . $mail->ErrorInfo]);
            }

        } else {
            $stmt2->close();
            $conexion->close();
            echo json_encode(["exito" => false, "mensaje" => "Error al actualizar el código."]);
        }

    } else {
        $stmt->close();
        $conexion->close();
        echo json_encode(["exito" => false, "mensaje" => "Usuario no encontrado."]);
    }
} else {
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
}
?>
