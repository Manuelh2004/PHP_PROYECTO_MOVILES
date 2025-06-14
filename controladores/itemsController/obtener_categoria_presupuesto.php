<?php 
require_once("../../modelos/categoria/categoria.php");

// Obtener el id_usuario desde GET o POST
$id_usuario = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : (isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0);

// Validar que se haya recibido un ID válido
if ($id_usuario > 0) {
    $rpta = MostrarCategoriaConPresupuesto($id_usuario);
} else {
    $rpta = array("error" => "ID de usuario no válido o no enviado.");
}

// Devolver resultado como JSON
echo json_encode($rpta);
?>
