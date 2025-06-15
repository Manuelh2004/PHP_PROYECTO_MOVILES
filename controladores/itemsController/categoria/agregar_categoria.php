<?php 
    $nom_categoria = $_POST['nom_categoria'];
    $est_categoria = $_POST['est_categoria'];

    // Llamar a la función
    require_once("../../../modelos/categoria/categoria.php");
    $rpta = RegistrarCategoria($nom_categoria, $est_categoria);
    // Responder a Android
    echo $rpta;
?>