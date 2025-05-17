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
?>