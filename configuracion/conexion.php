<?php
function conectar() {
    return mysqli_connect("localhost", "root", "", "bd_moviles", "3306");
}
?>
