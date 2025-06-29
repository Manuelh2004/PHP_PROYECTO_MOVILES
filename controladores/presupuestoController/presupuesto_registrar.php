<?php
// Recibir desde Android o Postman
$montoP = $_POST['montoP']; // Cambiado de $_GET a $_POST
$id_usuario = $_POST['id_usuario']; // Cambiado de $_GET a $_POST
$categoriaP = $_POST['categoriaP']; // Cambiado de $_GET a $_POST
$fechaInicioP = $_POST['fechaInicioP']; // Cambiado de $_GET a $_POST
$fechaFinP = $_POST['fechaFinP']; // Cambiado de $_GET a $_POST

// Llamar a la función
require_once("../../modelos/presupuesto/funcion_presupuesto.php");
$rpta = RegistrarPresupuesto($montoP,  $id_usuario, $categoriaP, $fechaInicioP, $fechaFinP);

// Responder a Android o Postman
echo $rpta;
?>
