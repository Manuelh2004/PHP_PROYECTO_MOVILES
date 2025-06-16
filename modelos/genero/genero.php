<?php 
    function MostrarGenero()
    {
        //Establecer la conexión a la BD
        require_once("../../configuracion/conexion.php");

        // Obtener la conexión
        $con = conectar();  // <--- Aquí llamas a la función conectar()

        //Query
        $sql = "SELECT id_genero as id, nom_genero as nombre FROM genero";

        //ejecutar el query
        $result = mysqli_query($con, $sql);

        $datos = array();
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            $datos[] = $row;
        }

        //cerrar conexión a BD
        mysqli_close($con);

        return $datos;
    }

    function RegistrarGenero($nom_genero){
        require_once("C:/wamp64/www/proyecto_moviles/configuracion/conexion.php");
        $con = conectar();

        // Para evitar SQL Injection, se recomienda preparar la sentencia.
        $sql = "INSERT INTO genero (nom_genero)
                VALUES (?)";

        $stmt = mysqli_prepare($con, $sql);

        if (!$stmt) {
            return "Error en la preparación de la consulta";
        }

        mysqli_stmt_bind_param($stmt, "s", $nom_categoria);

        $exec = mysqli_stmt_execute($stmt);

        if (!$exec) {
            $msg = "Error al registrar el genero";
        } else {
            $msg = "success";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $msg;
    }
?>