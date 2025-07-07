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
            m.est_movimiento,
            m.id_categoria,
            m.id_tipo_movimiento
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


   if (!empty($fecha_inicio)) {
        $sql .= " AND m.fech_movimiento BETWEEN '$fecha_inicio 00:00:00' AND '$fecha_inicio 23:59:59'";
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

   function ActualizarMovimiento($idMovimiento, $idTipoMovimientoNuevo, $idUsuario, $idCategoria, $monMovimientoNuevo, $fechMovimiento, $desMovimiento, $estMovimiento) {
    require_once("../../configuracion/conexion.php");
    $con = conectar();

    // 1. Obtener el valor anterior del movimiento
    $sqlAnterior = "SELECT mon_movimiento, id_tipo_movimiento FROM movimiento WHERE id_movimiento = ?";
    $stmtAnterior = mysqli_prepare($con, $sqlAnterior);
    mysqli_stmt_bind_param($stmtAnterior, "i", $idMovimiento);
    mysqli_stmt_execute($stmtAnterior);
    $resultAnterior = mysqli_stmt_get_result($stmtAnterior);

    if (!$resultAnterior || mysqli_num_rows($resultAnterior) === 0) {
        mysqli_stmt_close($stmtAnterior);
        mysqli_close($con);
        return "Error: Movimiento no encontrado.";
    }

    $row = mysqli_fetch_assoc($resultAnterior);
    $monMovimientoAnterior = (float) $row['mon_movimiento'];
    $idTipoMovimientoAnterior = (int) $row['id_tipo_movimiento'];
    mysqli_stmt_close($stmtAnterior);

    // 2. Calcular efecto del movimiento anterior y nuevo
    $efectoAnterior = ($idTipoMovimientoAnterior == 1) ? $monMovimientoAnterior : -$monMovimientoAnterior;
    $efectoNuevo    = ($idTipoMovimientoNuevo == 1) ? $monMovimientoNuevo : -$monMovimientoNuevo;

    // 3. Calcular diferencia a aplicar al presupuesto
    $ajuste = $efectoNuevo - $efectoAnterior;

    // 4. Actualizar presupuesto (pres_presupuesto)
    $sqlPresupuesto = "UPDATE presupuesto 
                       SET pres_presupuesto = pres_presupuesto + ?
                       WHERE id_usuario = ? AND id_categoria = ? AND est_presupuesto = 1";
    $stmtPres = mysqli_prepare($con, $sqlPresupuesto);
    mysqli_stmt_bind_param($stmtPres, "dii", $ajuste, $idUsuario, $idCategoria);
    mysqli_stmt_execute($stmtPres);
    mysqli_stmt_close($stmtPres);

    // 5. Actualizar movimiento
    $sqlUpdate = "UPDATE movimiento SET 
                    id_tipo_movimiento = ?, 
                    id_categoria = ?, 
                    mon_movimiento = ?, 
                    fech_movimiento = ?, 
                    des_movimiento = ?, 
                    est_movimiento = ? 
                  WHERE id_movimiento = ? AND id_usuario = ?";
    $stmt = mysqli_prepare($con, $sqlUpdate);
    mysqli_stmt_bind_param($stmt, "iiisssii", 
        $idTipoMovimientoNuevo, $idCategoria, $monMovimientoNuevo, 
        $fechMovimiento, $desMovimiento, $estMovimiento, 
        $idMovimiento, $idUsuario);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    return $success ? "success" : "Error al actualizar el movimiento";
}

?>
