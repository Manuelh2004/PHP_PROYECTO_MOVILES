<?php 
    $nom_genero = $_POST['nom_genero'];
    // Llamar a la función
    require_once("../../../modelos/genero/genero.php");
    $rpta = RegistrarGenero($nom_genero);
    // Responder a Android
    echo $rpta;
?>