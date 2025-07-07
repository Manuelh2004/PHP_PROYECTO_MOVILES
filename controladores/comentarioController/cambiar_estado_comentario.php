<?php
    require_once("../../configuracion/conexion.php");
    $con = conectar();

    if (isset($_POST['id_comentario'])) {
        $id = $_POST['id_comentario'];

        $sql = "SELECT est_comentario FROM comentario WHERE id_comentario = $id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $estadoActual = $row['est_comentario'];
            $nuevoEstado = ($estadoActual == 1) ? 0 : 1;

            $sqlUpdate = "UPDATE comentario SET est_comentario = $nuevoEstado WHERE id_comentario = $id";
            if (mysqli_query($con, $sqlUpdate)) {
                echo json_encode(["success" => true, "nuevo_estado" => $nuevoEstado]);
            } else {
                echo json_encode(["success" => false, "error" => mysqli_error($con)]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "Comentario no encontrado"]);
        }

        mysqli_close($con);
    } else {
        echo json_encode(["success" => false, "error" => "ID no proporcionado"]);
    }
?>