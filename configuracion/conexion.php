<?php
function conectar() {
    return mysqli_connect("localhost", "root","", "bd_proyecto_moviles", "3306");
}
?>
