<?php 
    function MostrarTipoDoc()
    {
       //Establecer la conexión a la BD
        require_once("../../configuracion/conexion.php");

        // Obtener la conexión
        $con = conectar();  // <--- Aquí llamas a la función conectar()
        
        //Query
        $sql = "SELECT id_tipo_documento as id, nom_tipo_documento as nombre 
        FROM tipo_documento";

        //ejecutar el query
        $result = mysqli_query($con,$sql);

        $datos = array();
        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
        {
            $datos[] = $row;
        }

        //cerrar conexión a BD
        mysqli_close($con);

        return $datos;

    }

    function RegistrarTipoDocumento($nom_tipo_documento){
        require_once("C:/wamp64/www/proyecto_moviles/configuracion/conexion.php");
        $con = conectar();

        // Para evitar SQL Injection, se recomienda preparar la sentencia.
        $sql = "INSERT INTO tipo_documento (nom_tipo_documento)
                VALUES (?)";

        $stmt = mysqli_prepare($con, $sql);

        if (!$stmt) {
            return "Error en la preparación de la consulta";
        }

        mysqli_stmt_bind_param($stmt, "s", $nom_tipo_documento);

        $exec = mysqli_stmt_execute($stmt);

        if (!$exec) {
            $msg = "Error al registrar el tipo_documento";
        } else {
            $msg = "success";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);

        return $msg;
    }
?>