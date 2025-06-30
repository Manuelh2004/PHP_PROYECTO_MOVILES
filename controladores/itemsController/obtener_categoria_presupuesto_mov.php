<?php
// Incluir el archivo de conexión a la base de datos
require_once("../../configuracion/conexion.php");
$con = conectar();

// Obtener el id_usuario desde el parámetro POST
$id_usuario = $_POST['id_usuario'] ?? null;

// Log: Verificar si el parámetro id_usuario llegó correctamente
error_log("id_usuario recibido: " . $id_usuario);

// Validar que se reciba el id_usuario
if (!$id_usuario) {
    echo json_encode(["error" => "Falta id_usuario"]);
    exit;
}

// Preparar la consulta para prevenir inyecciones SQL
$sql = "
    SELECT DISTINCT c.id_categoria, c.nom_categoria 
    FROM categoria c
    JOIN movimiento m ON m.id_categoria = c.id_categoria
    WHERE m.id_usuario = ? AND c.est_categoria = 1
";

// Preparar la consulta
$stmt = mysqli_prepare($con, $sql);

// Vincular el parámetro
mysqli_stmt_bind_param($stmt, "i", $id_usuario);

// Ejecutar la consulta
mysqli_stmt_execute($stmt);

// Obtener el resultado
$result = mysqli_stmt_get_result($stmt);

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
    $categorias = array();
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $categorias[] = array(
            'id_categoria' => $row['id_categoria'],
            'nombre' => $row['nom_categoria']
        );
    }

    // Log: Mostrar el resultado
    error_log("Categorías encontradas: " . json_encode($categorias));

    echo json_encode($categorias);
} else {
    // Log: Si no hay categorías, mostramos el error
    error_log("No se encontraron categorías para el usuario con id_usuario: " . $id_usuario);
    echo json_encode(["error" => "No se encontraron categorías para este usuario"]);
}

// Cerrar la conexión a la base de datos
mysqli_close($con);
?>
