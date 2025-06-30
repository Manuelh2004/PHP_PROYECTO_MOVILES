    <?php
    function RegistrarMovimiento($id_tipo_movimiento, $id_usuario, $id_categoria, $mon_movimiento, $des_movimiento, $est_movimiento) {
    
    require_once("../../configuracion/conexion.php");
    $con = conectar();

    // La columna fech_movimiento ahora es CURRENT_TIMESTAMP en la base de datos
    $sql = "INSERT INTO movimiento (id_tipo_movimiento, id_usuario, id_categoria, mon_movimiento, des_movimiento, est_movimiento)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($con, $sql);

    if (!$stmt) {
        return "Error en la preparación de la consulta";
    }

    mysqli_stmt_bind_param($stmt, "iiidss", 
        $id_tipo_movimiento, 
        $id_usuario, 
        $id_categoria, 
        $mon_movimiento, 
        $des_movimiento, 
        $est_movimiento
    );

    $exec = mysqli_stmt_execute($stmt);

    $msg = $exec ? "success" : "Error al registrar movimiento";

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    return $msg;
}

    function MostrarMovimiento(){
    // Establecer la conexión a la BD
    require_once("../../configuracion/conexion.php");
    $con = conectar();

    // Obtener los parámetros del GET
    $fecha_inicio = $_GET['fecha_inicio'] ?? null;
    $fecha_fin = $_GET['fecha_fin'] ?? null;
    $id_usuario = $_GET['id_usuario'] ?? null;
    $id_categoria = $_GET['id_categoria'] ?? null;  // Añadido para obtener el filtro de categoría

    // Validar que el id_usuario esté presente
    if (!$id_usuario) {
        echo json_encode(["error" => "Falta id_usuario"]);
        exit;
    }

    // Consulta base
    $sql = "
        SELECT 
            m.id_movimiento,
            CONCAT(u.nom_usuario, ' ', u.ape_usuario) AS usuario,
            tm.nom_tipo_movimiento,
            c.nom_categoria,
            m.mon_movimiento,
            m.fech_movimiento,
            m.des_movimiento,
            m.est_movimiento
        FROM movimiento m
        LEFT JOIN usuario u ON m.id_usuario = u.id_usuario
        LEFT JOIN tipo_movimiento tm ON m.id_tipo_movimiento = tm.id_tipo_movimiento
        LEFT JOIN categoria c ON m.id_categoria = c.id_categoria
        WHERE m.id_usuario = $id_usuario
    ";

    // Filtrar por categoría si está disponible
    if ($id_categoria) {
        $sql .= " AND m.id_categoria = $id_categoria";
    }

    // Filtrar por fechas si están disponibles
    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND m.fech_movimiento BETWEEN '$fecha_inicio' AND '$fecha_fin'";
    }

    $sql .= " ORDER BY m.id_movimiento DESC";

    // Ejecutar el query
    $result = mysqli_query($con, $sql);

    $datos = array();
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $datos[] = $row;
    }

    // Cerrar conexión a BD
    mysqli_close($con);

    return $datos;
}
    //function ActualizarMovimiento(){}

    function ActualizarMovimiento($idMovimiento, $idTipoMovimiento, $idUsuario, $idCategoria, $monMovimiento, $fechMovimiento, $desMovimiento, $estMovimiento) {
    require_once("../../configuracion/conexion.php");

    // Conectar a la base de datos
    $con = conectar();

    // Preparar la consulta SQL para actualizar el movimiento usando sentencias preparadas
    $sql = "UPDATE movimiento SET 
               id_tipo_movimiento = ?, 
               id_categoria = ?, 
               mon_movimiento = ?, 
               fech_movimiento = ?, 
               des_movimiento = ?, 
               est_movimiento = ? 
           WHERE id_movimiento = ? AND id_usuario = ?";

    // Preparar la sentencia
    $stmt = mysqli_prepare($con, $sql);
    if (!$stmt) {
        return "Error en la preparación de la consulta: " . mysqli_error($con);
    }

    // Vincular los parámetros de la consulta
    mysqli_stmt_bind_param($stmt, "iiisssii", $idTipoMovimiento, $idCategoria, $monMovimiento, $fechMovimiento, $desMovimiento, $estMovimiento, $idMovimiento, $idUsuario);

    // Ejecutar la sentencia
    $exec = mysqli_stmt_execute($stmt);
    if (!$exec) {
        return "Error al actualizar el movimiento: " . mysqli_stmt_error($stmt);
    } else {
        return "success"; // Indicar que la actualización fue exitosa
    }

    // Cerrar la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>
