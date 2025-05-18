<?php
function RegistrarPresupuesto($montoP, $id_usuario, $categoriaP, $fechaInicioP, $fechaFinP){
    
    require_once("../../configuracion/conexion.php");

    $con = conectar();

    $sql = "INSERT INTO presupuesto() VALUES 
    (
    null,
    '$id_usuario',
    '$categoriaP',
    '$montoP',
    '$fechaInicioP',
    '$fechaFinP',
    1
    )";
    
    //ejecutar el query
    $result = mysqli_query($con,$sql);

    if(!$result) //si es False
    {
        $msg = "Error en la ejecución de la consulta";
    }
    else
    {
        $msg = "Registrado correctamente";
    }

    //cerrar conexión a BD
    mysqli_close($con);

    return $msg;
}
function MostrarPresupuestos()
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
            INNER JOIN categoria c ON p.id_categoria = c.id_categoria";

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
function ActualizarPresupuesto($idPresupuesto, $montoP, $idUsuario, $categoriaP, $fechaInicioP, $fechaFinP){
    
    require_once("../../configuracion/conexion.php");

    $con = conectar();

    $sql = "UPDATE presupuesto SET 
            pres_presupuesto = '$montoP',
            id_usuario = '$idUsuario',
            id_categoria = '$categoriaP',
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
?>