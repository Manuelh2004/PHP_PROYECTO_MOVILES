<?php
// Incluir el archivo donde está definida la función ActualizarMovimiento
require_once("../../modelos/movimiento/movimiento.php"); // Ajusta la ruta según corresponda

// Verificar que los parámetros están presentes
if (isset($_POST['id_movimiento']) && isset($_POST['id_usuario']) && isset($_POST['id_tipo_movimiento']) && isset($_POST['id_categoria']) && isset($_POST['mon_movimiento']) && isset($_POST['fech_movimiento']) && isset($_POST['des_movimiento']) && isset($_POST['est_movimiento'])) {
    // Recuperación de parámetros
    $idMovimiento = $_POST['id_movimiento'];
    $idUsuario = $_POST['id_usuario'];
    $idTipoMovimiento = $_POST['id_tipo_movimiento'];
    $idCategoria = $_POST['id_categoria'];
    $monMovimiento = $_POST['mon_movimiento'];
    $fechMovimiento = $_POST['fech_movimiento'];
    $desMovimiento = $_POST['des_movimiento'];
    $idEstado = $_POST['est_movimiento'];
    
    // Actualizar el movimiento
    $rpta = ActualizarMovimiento($idMovimiento, $idTipoMovimiento, $idUsuario, $idCategoria, $monMovimiento, $fechMovimiento, $desMovimiento, $idEstado);
    echo $rpta;
} else {
    echo "Faltan parámetros para realizar la actualización.";
}
?>
