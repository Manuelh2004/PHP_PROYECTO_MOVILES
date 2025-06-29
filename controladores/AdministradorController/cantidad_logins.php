<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once("../../configuracion/conexion.php");

$con = conectar();

// Verifica conexión
if ($con->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $con->connect_error]));
}

// Configurar idioma de los días si deseas que salgan en español
$con->query("SET lc_time_names = 'es_ES'");

// Consulta agrupando por fecha de la semana
/*$sql = "
    SELECT 
        DATE_FORMAT(fre_usuario, '%W') AS dia,
        COUNT(*) AS cantidad
    FROM usuario
    WHERE fre_usuario >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(fre_usuario)
    ORDER BY DATE(fre_usuario)
";*/

// todos hasta los antiguos.
$sql = "
    SELECT 
        DATE_FORMAT(fre_usuario, '%W') AS dia,
        COUNT(*) AS cantidad
    FROM usuario
    WHERE fre_usuario IS NOT NULL
    GROUP BY DATE(fre_usuario)
    ORDER BY DATE(fre_usuario)
";

$result = $con->query($sql);
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "dia" => ucfirst(strtolower($row["dia"])), // Ejemplo: 'Lunes'
            "cantidad" => (int)$row["cantidad"]
        ];
    }
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);

$con->close();
?>
