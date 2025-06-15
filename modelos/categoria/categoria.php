<?php 
    function MostrarCategoriaConPresupuesto($id_usuario)
    {
        // Establecer la conexión a la BD
        require_once("../../configuracion/conexion.php");

        // Obtener la conexión
        $con = conectar();

        // Preparar la consulta SQL con filtro por id_usuario
        $sql = "SELECT DISTINCT c.id_categoria AS id, c.nom_categoria AS nombre
                FROM categoria c
                INNER JOIN presupuesto p ON c.id_categoria = p.id_categoria
                WHERE p.id_usuario = ?";

        // Preparar la sentencia
        if ($stmt = mysqli_prepare($con, $sql)) {
            // Vincular parámetros
            mysqli_stmt_bind_param($stmt, "i", $id_usuario);

            // Ejecutar la consulta
            mysqli_stmt_execute($stmt);

            // Obtener resultado
            $result = mysqli_stmt_get_result($stmt);

            $datos = array();
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $datos[] = $row;
            }

            // Cerrar sentencia
            mysqli_stmt_close($stmt);
        } else {
            // Manejo de error si falla la preparación
            error_log("Error al preparar la consulta: " . mysqli_error($con));
            $datos = array(); // Devuelve vacío en caso de error
        }

        // Cerrar conexión a BD
        mysqli_close($con);

        return $datos;
    }

    function RegistrarCategoria($nom_categoria, $est_categoria){
        require_once("C:/wamp64/www/proyecto_moviles/configuracion/conexion.php");
        $con = conectar();

        // Para evitar SQL Injection, se recomienda preparar la sentencia.
        $sql = "INSERT INTO categoria (nom_categoria, est_categoria)
                VALUES (?, ?)";

        $stmt = mysqli_prepare($con, $sql);

        if (!$stmt) {
            return "Error en la preparación de la consulta";
        }

        mysqli_stmt_bind_param($stmt, "si", $nom_categoria, $est_categoria);

        $exec = mysqli_stmt_execute($stmt);

        if (!$exec) {
            $msg = "Error al registrar la categoria";
        } else {
            $msg = "success";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $msg;
    }
?>
