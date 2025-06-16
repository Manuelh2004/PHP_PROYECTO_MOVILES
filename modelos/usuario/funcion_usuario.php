<?php
function ValidarUsuario($id_usuario) {
    // Establecer conexión a la BD
    require_once("../../configuracion/conexion.php");

    $con = conectar();

    // Query
    $sql = "SELECT * FROM usuario u
        INNER JOIN tipo_usuario c ON u.id_tipo_usuario = c.id_tipo_usuario 
        WHERE id_usuario = '$id_usuario'";

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