<?php 
    $nom_tipo_documento = $_POST['nom_tipo_documento'];
    // Llamar a la función
    require_once("../../../modelos/tipo_documento/tipo_documento.php");
    $rpta = RegistrarTipoDocumento($nom_tipo_documento);
    // Responder a Android
    echo $rpta;
?>