<?php
    // Recibir desde Android
    $id_categoria = $_POST['id_categoria'];
    $id_usuario = $_POST['id_usuario'];
    $monto_actualizado = $_POST['monto_actualizado'];
    // Llamar a la función
    require_once("../../modelos/presupuesto/funcion_presupuesto.php");
    $rpta = ActualizarPresupuestoPorMovimiento($id_categoria, $id_usuario, $monto_actualizado);
    // Responder a Android
    echo $rpta;
?>