<?php
function conectar() {
    return mysqli_connect("localhost", "root","", "bd_nueva", "3306");
}
?>
