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
?>