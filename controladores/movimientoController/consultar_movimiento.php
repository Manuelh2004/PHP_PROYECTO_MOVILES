<?php
// Establecer la conexión a la base de datos
require_once('../../configuracion/conexion.php');

// Obtener el id del movimiento a consultar
$idMovimiento = $_GET['idMovimiento'] ?? $_POST['idMovimiento'];
$idUsuario = $_GET['idUsuario'] ?? $_POST['idUsuario'];

if (empty($idMovimiento) || empty($idUsuario)) {
    echo json_encode(array("error" => "Faltan parámetros"));
    exit();
}

// Conectar a la base de datos
$con = conectar();

// Consulta para obtener el movimiento
$sql = "
     SELECT 
        m.id_movimiento,
        CONCAT(u.nom_usuario, ' ', u.ape_usuario) AS usuario,
        tm.id_tipo_movimiento,  -- Agregado id_tipo_movimiento
        tm.nom_tipo_movimiento, -- Mantener el nombre del tipo de movimiento
        m.id_categoria,         -- id_categoria para que lo devuelvas
        c.nom_categoria,
        m.mon_movimiento,
        m.fech_movimiento,
        m.des_movimiento,
        m.est_movimiento
    FROM movimiento m
    LEFT JOIN usuario u ON m.id_usuario = u.id_usuario
    LEFT JOIN tipo_movimiento tm ON m.id_tipo_movimiento = tm.id_tipo_movimiento
    LEFT JOIN categoria c ON m.id_categoria = c.id_categoria
    WHERE m.id_movimiento = ? AND m.id_usuario = ?
";

// Preparar la sentencia
$stmt = mysqli_prepare($con, $sql);

if (!$stmt) {
    echo json_encode(array("error" => "Error en la preparación de la consulta: " . mysqli_error($con)));
    exit();
}

// Vincular los parámetros
mysqli_stmt_bind_param($stmt, "ii", $idMovimiento, $idUsuario);

// Ejecutar la sentencia
mysqli_stmt_execute($stmt);

// Obtener el resultado
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $movimiento = mysqli_fetch_assoc($result);
    echo json_encode($movimiento);  // Devolver los datos como JSON
} else {
    echo json_encode(array("error" => "Movimiento no encontrado"));
}

// Cerrar la conexión
mysqli_stmt_close($stmt);
mysqli_close($con);

?>
