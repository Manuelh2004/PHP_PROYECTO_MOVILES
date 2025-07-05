<?php 
    function ListarComentario()
{
    require_once("../../configuracion/conexion.php");
    $con = conectar();

    $estado = isset($_GET['estado']) ? $_GET['estado'] : '';
    $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
    $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

    $sql = "SELECT id_comentario, men_comentario, fre_comentario, est_comentario FROM comentario WHERE 1";

    if ($estado != '') {
        if ($estado == "Revisado") {
            $sql .= " AND est_comentario = 0";
        } else if ($estado == "No revisado") {
            $sql .= " AND est_comentario = 1";
        }
    }

    if ($fecha_inicio != '') {
        $sql .= " AND DATE(fre_comentario) >= '$fecha_inicio'";
    }

    if ($fecha_fin != '') {
        $sql .= " AND DATE(fre_comentario) <= '$fecha_fin'";
    }

    $result = mysqli_query($con, $sql);
    $datos = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $datos[] = $row;
        }
    } else {
        echo "Error en la consulta: " . mysqli_error($con);
    }

    mysqli_close($con);
    return $datos;
}

 function RegistrarComentario($id_usuario, $men_comentario, $est_comentario)
    {
      // Establecer la conexión a la BD
        require_once("../../configuracion/conexion.php");

        // Obtener la conexión
        $con = conectar();

        // Crear la consulta para insertar el comentario
        $query = "INSERT INTO comentario (id_usuario, men_comentario, est_comentario) 
                VALUES ('$id_usuario', '$men_comentario', '$est_comentario')";

        // Ejecutar la consulta
        if (mysqli_query($con, $query)) {
            // Si la inserción es exitosa, devolver 'success'
            return "success";
        } else {
            // Si hay un error, devolver 'error'
            return "error";
        }

        // Cerrar la conexión
        mysqli_close($con);
    }
?>
