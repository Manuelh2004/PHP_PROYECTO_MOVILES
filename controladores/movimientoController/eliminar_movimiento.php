<?php
// Incluir archivo de configuración de la base de datos
require_once("../../configuracion/conexion.php");

if (isset($_POST['id_movimiento'])) {
    // Recuperar el ID del movimiento
    $idMovimiento = $_POST['id_movimiento'];

    // Conectar a la base de datos
    $con = conectar();

    // Consulta SQL para eliminar el movimiento
    $sql = "DELETE FROM movimiento WHERE id_movimiento = ?";

    // Preparar la sentencia
    $stmt = mysqli_prepare($con, $sql);
    
    if (!$stmt) {
        echo json_encode(array("error" => "Error en la preparación de la consulta: " . mysqli_error($con)));
        exit();
    }

    // Vincular el parámetro
    mysqli_stmt_bind_param($stmt, "i", $idMovimiento);

    // Ejecutar la sentencia
    $exec = mysqli_stmt_execute($stmt);

    if (!$exec) {
        echo json_encode(array("error" => "Error al eliminar el movimiento: " . mysqli_stmt_error($stmt)));
    } else {
        echo json_encode(array("success" => "Movimiento eliminado correctamente"));
    }

    // Cerrar la sentencia y la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($con);

} else {
    echo json_encode(array("error" => "Faltan parámetros"));
}
?>
