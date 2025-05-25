<?php
header('Content-Type: application/json');
include '../../configuracion/conexion.php';

$conexion = conectar();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $codigo = $_POST['codigo'] ?? '';

    if (!$email || !$codigo) {
        echo json_encode(["exito" => false, "mensaje" => "Faltan datos requeridos."]);
        exit;
    }

   // Consulta
        $stmt = $conexion->prepare("SELECT cod_usuario, esver_usuario FROM usuario WHERE em_usuario = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($codigo_bd, $es_verificado);

        if ($stmt->fetch()) {
            if ($es_verificado == 1) {
                echo json_encode(["exito" => false, "mensaje" => "El email ya está verificado."]);
            } else if ($codigo === $codigo_bd) {
                $stmt->close();

                $nuevoEstado = 1;
                $stmt2 = $conexion->prepare("UPDATE usuario SET esver_usuario = ? WHERE em_usuario = ?");
                $stmt2->bind_param("is", $nuevoEstado, $email);
                $stmt2->execute();
                $stmt2->close();

                echo json_encode(["exito" => true, "mensaje" => "Código correcto. Email verificado."]);
            } else {
                echo json_encode(["exito" => false, "mensaje" => "Código incorrecto."]);
            }        

        } else {
            echo json_encode(["exito" => false, "mensaje" => "Usuario no encontrado."]);
        }

        $stmt->close();
        $conexion->close();
    } else {
        echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
    }
?>