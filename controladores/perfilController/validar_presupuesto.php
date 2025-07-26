<?php
include '../../configuracion/conexion.php';
$conn=conectar();

$id_usuario = $_GET['id_usuario'];

$query = "SELECT COUNT(*) AS total FROM presupuesto WHERE id_usuario = $id_usuario AND est_presupuesto = 1";

// Debug: imprime la query
error_log("QUERY: $query");

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    error_log("TOTAL: " . $row['total']);
    if ($row['total'] > 0) {
        echo "existe";
    } else {
        echo "no_existe";
    }
} else {
    echo "error";
    error_log("MYSQL ERROR: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
