<?php
function RegistrarMovimiento($id_tipo_movimiento, $id_usuario, $id_categoria, $mon_movimiento, $fech_movimiento, $des_movimiento, $est_movimiento) {
    
    require_once("../../configuracion/conexion.php");
    $con = conectar();

    // Para evitar SQL Injection, se recomienda preparar la sentencia.
    $sql = "INSERT INTO movimiento (id_tipo_movimiento, id_usuario, id_categoria, mon_movimiento, fech_movimiento, des_movimiento, est_movimiento)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        return "Error en la preparación de la consulta";
    }

    mysqli_stmt_bind_param($stmt, "iiisssi", $id_tipo_movimiento, $id_usuario, $id_categoria, $mon_movimiento, $fech_movimiento, $des_movimiento, $est_movimiento);

    $exec = mysqli_stmt_execute($stmt);

    if (!$exec) {
        $msg = "Error al registrar movimiento";
    } else {
        $msg = "success";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    return $msg;
}
?>
