<?php
function ValidarUsuario($email, $password) {
    // Establecer conexión a la BD
    require_once("conexion.php");

    // Query
    $sql = "SELECT * FROM usuario WHERE em_usuario = '$email' AND pas_usuario = '$password'";

    // Ejecutar la consulta
    $result = mysqli_query($con, $sql);

    $datos = array();
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $datos[] = $row;
    }

    // Cerrar conexión
    mysqli_close($con);

    return $datos;
}
function ObtenerUsuario($id_usuario) {
    // Incluir la conexión a la base de datos
    require_once("../../configuracion/conexion.php");

    // Obtener la conexión
    $con = conectar();

    // Consulta SQL para obtener los datos del usuario por id_usuario
    $query = "SELECT nom_usuario, ape_usuario, num_usuario, fna_usuario, tel_usuario 
              FROM usuario 
              WHERE id_usuario = '$id_usuario'";

    // Ejecutar la consulta
    $result = mysqli_query($con, $query);

    // Verificar si se encontró el usuario
    if (mysqli_num_rows($result) > 0) {
        // Si se encontró el usuario, obtener los datos
        $row = mysqli_fetch_assoc($result);

        // Convertir los datos a formato JSON y devolverlos
        return json_encode($row);
    } else {
        // Si no se encuentra el usuario, devolver error
        return "error";
    }

    // Cerrar la conexión
    mysqli_close($con);
}

?>