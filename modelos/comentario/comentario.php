<?php 
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
