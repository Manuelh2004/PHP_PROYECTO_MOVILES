<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require '../../vendor/autoload.php';
require_once("../../configuracion/conexion.php");

// Obtener id_usuario y correo del POST
$id_usuario = $_POST['id_usuario'] ?? null;
$email = $_POST['email'] ?? null;
$nombre = $_POST['nombre'] ?? "Usuario";

if (!$id_usuario || !$email) {
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
    exit;
}

$con = conectar();

// Consulta para obtener presupuestos del usuario
$presupuesto_sql = "
SELECT 
    p.id_presupuesto, p.pres_presupuesto, p.pres_act_presupuesto, p.fini_presupuesto, 
    p.ffin_presupuesto, c.nom_categoria, p.id_categoria
FROM presupuesto p
JOIN categoria c ON p.id_categoria = c.id_categoria
WHERE p.id_usuario = $id_usuario AND p.est_presupuesto = 1
";

$presupuesto_res = mysqli_query($con, $presupuesto_sql);

if (!$presupuesto_res || mysqli_num_rows($presupuesto_res) === 0) {
    echo json_encode(["success" => false, "error" => "No hay presupuestos activos"]);
    exit;
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Reporte de Movimientos");

$rowIndex = 1;

// Estilo común de bordes
$defaultBorder = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];

while ($pres = mysqli_fetch_assoc($presupuesto_res)) {
    // Título
    $sheet->setCellValue("A$rowIndex", "📊 Presupuesto: " . $pres['nom_categoria']);
    $sheet->mergeCells("A$rowIndex:D$rowIndex");
    $sheet->getStyle("A$rowIndex")->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle("A$rowIndex")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCE5FF');
    $rowIndex++;

    // Info presupuestal
    $sheet->setCellValue("A$rowIndex", "Presupuesto Total:");
    $sheet->setCellValue("B$rowIndex", $pres['pres_presupuesto']);
    $sheet->setCellValue("C$rowIndex", "Presupuesto Actual:");
    $sheet->setCellValue("D$rowIndex", $pres['pres_act_presupuesto']);
    $sheet->getStyle("A$rowIndex:D$rowIndex")->applyFromArray($defaultBorder);
    $rowIndex++;

    $sheet->setCellValue("A$rowIndex", "Fecha Inicio:");
    $sheet->setCellValue("B$rowIndex", $pres['fini_presupuesto']);
    $sheet->setCellValue("C$rowIndex", "Fecha Fin:");
    $sheet->setCellValue("D$rowIndex", $pres['ffin_presupuesto']);
    $sheet->getStyle("A$rowIndex:D$rowIndex")->applyFromArray($defaultBorder);
    $rowIndex++;

    // Encabezados de tabla
    $rowIndex++;
    $sheet->setCellValue("A$rowIndex", "Fecha");
    $sheet->setCellValue("B$rowIndex", "Monto");
    $sheet->setCellValue("C$rowIndex", "Descripción");
    $sheet->setCellValue("D$rowIndex", "Estado");

    $sheet->getStyle("A$rowIndex:D$rowIndex")->getFont()->setBold(true);
    $sheet->getStyle("A$rowIndex:D$rowIndex")->getFill()->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFE699');
    $sheet->getStyle("A$rowIndex:D$rowIndex")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("A$rowIndex:D$rowIndex")->applyFromArray($defaultBorder);
    $rowIndex++;

    // Movimientos
    $mov_sql = "
        SELECT fech_movimiento, mon_movimiento, des_movimiento, est_movimiento
        FROM movimiento
        WHERE id_usuario = $id_usuario
        AND id_categoria = {$pres['id_categoria']}
    ";
    $mov_res = mysqli_query($con, $mov_sql);

    while ($mov = mysqli_fetch_assoc($mov_res)) {
        $sheet->setCellValue("A$rowIndex", $mov['fech_movimiento']);
        $sheet->setCellValue("B$rowIndex", $mov['mon_movimiento']);
        $sheet->setCellValue("C$rowIndex", $mov['des_movimiento']);
        $sheet->setCellValue("D$rowIndex", $mov['est_movimiento'] == 1 ? "Activo" : "Inactivo");
        $sheet->getStyle("A$rowIndex:D$rowIndex")->applyFromArray($defaultBorder);
        $rowIndex++;
    }

    $rowIndex += 3;
}

// Autoajustar columnas
foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Guardar archivo temporal
$filename = "Reporte_" . date("Ymd_His") . ".xlsx";
$filepath = sys_get_temp_dir() . "/" . $filename;

$writer = new Xlsx($spreadsheet);
$writer->save($filepath);

// Enviar por correo
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'alexxanderay@gmail.com';
    $mail->Password = 'qglz akop gbjo nsom';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('alexxanderay@gmail.com', 'Pockit');
    $mail->addAddress($email, $nombre);
    $mail->Subject = 'Reporte de Presupuestos';
    $mail->Body = "Hola $nombre,\n\nAdjuntamos el reporte de tus movimientos organizados por presupuestos activos.";

    $mail->addAttachment($filepath, $filename);

    $mail->send();
    unlink($filepath); // Eliminar archivo temporal
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $mail->ErrorInfo]);
}
?>
