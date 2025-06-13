<?php
    // Recibir desde Android
    $uid_firebase = $_POST['uid_firebase'];    
    require_once("../../modelos/usuario/usuario.php");
    $rpta = ObtenerRol($uid_firebase);
    // Responder a Android
    echo $rpta;
?>