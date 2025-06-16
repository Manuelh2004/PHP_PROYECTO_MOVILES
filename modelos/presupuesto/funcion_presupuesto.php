<?php
function RegistrarPresupuesto($montoP, $id_usuario, $categoriaP, $fechaInicioP, $fechaFinP){
    require_once("../../configuracion/conexion.php");

    $con = conectar();
    $presupuesto_actual = $montoP;
    $estado_presupuesto = 1; // Asumimos que el estado por defecto es 1 (activo)

    // Corregir la consulta SQL
    $sql = "INSERT INTO presupuesto (id_usuario, id_categoria, pres_presupuesto, pres_act_presupuesto, fini_presupuesto, ffin_presupuesto, est_presupuesto) 
            VALUES ('$id_usuario', '$categoriaP', '$montoP', '$presupuesto_actual', '$fechaInicioP', '$fechaFinP', '$estado_presupuesto')";

    // Ejecutar el query
    $result = mysqli_query($con, $sql);

    if(!$result) {
        $msg = "Error en la ejecución de la consulta";
    } else {
        $msg = "Registrado correctamente";
    }

    // Cerrar conexión a BD
    mysqli_close($con);

    return $msg;
}

function MostrarPresupuestos($id_Usuario)
{
    //Establecer la conexión a la BD
    require_once("../../configuracion/conexion.php");

    $con = conectar();

    //Query
    $sql = "SELECT 
            p.id_presupuesto,
            p.pres_presupuesto,
            p.fini_presupuesto,
            p.ffin_presupuesto,
            p.id_categoria,
            c.nom_categoria FROM presupuesto p
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria WHERE p.id_usuario = '$id_Usuario'";

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
function ConsultarPresupuestos($idPresupuesto){
    require_once("../../configuracion/conexion.php");

    $con = conectar();

    //Query
    $sql = "SELECT 
            p.id_presupuesto,
            p.pres_presupuesto,
            p.fini_presupuesto,
            p.ffin_presupuesto,
            p.id_categoria 
            FROM presupuesto p where p.id_presupuesto = '$idPresupuesto'";
    
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
function ActualizarPresupuesto($idPresupuesto, $montoP, $montoActualp, $idUsuario, $categoriaP, $fechaInicioP, $fechaFinP){
    
    require_once("../../configuracion/conexion.php");

    $con = conectar();

    $sql = "UPDATE presupuesto SET
            pres_presupuesto = '$montoP',
            id_usuario = '$idUsuario',
            id_categoria = '$categoriaP',
            pres_act_presupuesto = '$montoActualp',
            fini_presupuesto = '$fechaInicioP',
            ffin_presupuesto = '$fechaFinP'
        WHERE id_presupuesto = '$idPresupuesto'";
    
    //ejecutar el query
    $result = mysqli_query($con,$sql);

    if(!$result) //si es False
    {
        $msg = "Error en la ejecución de la consulta";
    }
    else
    {
        $msg = "Actualizado correctamente";
    }

    //cerrar conexión a BD
    mysqli_close($con);

    return $msg;
}
function EliminarPresupuestos($idPresupuesto){

    require_once("../../configuracion/conexion.php");

    $con = conectar();

    $sql = "DELETE FROM presupuesto WHERE id_presupuesto = '$idPresupuesto'";

    //ejecutar el query
    $result = mysqli_query($con,$sql);

    if(!$result) //si es False
    {
        $msg = "Error en la ejecución de la consulta";
    }
    else
    {
        $msg = "Eliminado correctamente";
    }

    //cerrar conexión a BD
    mysqli_close($con);

    return $msg;
}


function ActualizarPresupuestoPorMovimiento($id_categoria, $id_usuario, $monto_actualizado){
    // Incluir la conexión a la base de datos
    require_once("../../configuracion/conexion.php");
    $con = conectar();

    // Primero, obtenemos el presupuesto actual de la categoría y usuario
    $query = "SELECT * FROM presupuesto WHERE id_categoria = '$id_categoria' AND id_usuario = '$id_usuario'";
    $result = mysqli_query($con, $query);

    // Verificamos si se encontró un presupuesto
    if(mysqli_num_rows($result) > 0) {
        // Si encontramos el presupuesto, obtenemos los datos
        $presupuesto = mysqli_fetch_assoc($result);

        // Actualizamos el presupuesto actual (pres_act_presupuesto)
        $nuevo_presupuesto = $presupuesto['pres_act_presupuesto'] + $monto_actualizado;

        // Actualizamos el presupuesto en la base de datos
        $updateQuery = "UPDATE presupuesto SET pres_act_presupuesto = '$nuevo_presupuesto' 
                        WHERE id_categoria = '$id_categoria' AND id_usuario = '$id_usuario'";

        // Ejecutamos la actualización
        if (mysqli_query($con, $updateQuery)) {
            // Si la actualización fue exitosa, respondemos con "success"
            return "success";
        } else {
            // Si hubo un error al actualizar
            return "error_al_actualizar";
        }
    } else {
        // Si no se encontró presupuesto para la categoría y usuario, podemos crear uno o devolver un error
        return "presupuesto_no_encontrado";
    }

    // Cerramos la conexión a la base de datos
    mysqli_close($con);
    }
?>