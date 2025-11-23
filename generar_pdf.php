<?php
require('fpdf/fpdf.php');

$conexion = new mysqli("localhost", "root", "", "actas_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM actas WHERE id = $id";
$result = $conexion->query($sql);
if ($result->num_rows == 0) {
    die("Acta no encontrada");
}
$acta = $result->fetch_assoc();

// --- Datos dinámicos ---
$codigo = strtoupper($acta['codigo_tarjeta']);
$solicitante = strtoupper($acta['solicita']);
$usuario = strtoupper($acta['nombre']);
$fecha = date('d-m-Y', strtotime($acta['fecha_creacion']));

// --- Crear PDF ---
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',11);

// Logo
$pdf->Image('images/logo-hospital.png',10,8,25);
$pdf->Ln(8);

$pdf->Cell(0,8,utf8_decode('CONTROL CENTRALIZADO'),0,1,'C');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,8,utf8_decode('Hospital Dr. Gustavo Fricke'),0,1,'C');


$pdf->SetFont('Arial','',11);
$pdf->Cell(0,8,utf8_decode("FS/" . $fecha),0,1,'R');
$pdf->Ln(4);

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,8,utf8_decode('ACTA DE ENTREGA'),0,1,'C');
$pdf->Ln(8);

$pdf->SetFont('Arial','',11);
$texto = "La tarjeta de control de acceso es de vital importancia para todos los funcionarios del hospital, ya que les permite acceder a diferentes áreas restringidas de acuerdo a su función o autorización.";
$pdf->MultiCell(0,7,utf8_decode($texto));
$pdf->Ln(5);

// ----------------------------------------------------
// TABLA: Código Tarjeta / Cantidad
// ----------------------------------------------------
$pdf->Ln(8);
$pdf->SetFont('Arial','B',11);

// Definir colores (gris claro para cabecera)
$pdf->SetFillColor(230, 230, 230);
$pdf->SetDrawColor(180, 180, 180);

// Anchos de columnas
$col1 = 95;
$col2 = 95;

// Posición centrada
$x_start = ($pdf->GetPageWidth() - ($col1 + $col2)) / 2;
$pdf->SetX($x_start);

// Cabecera
$pdf->Cell($col1, 8, utf8_decode('Código Tarjeta'), 1, 0, 'C', true);
$pdf->Cell($col2, 8, utf8_decode('Solicita'), 1, 1, 'C', true);

// Fila de datos
$pdf->SetFont('Arial','',11);
$pdf->SetX($x_start);
$pdf->Cell($col1, 8, utf8_decode($codigo), 1, 0, 'C');
$pdf->Cell($col2, 8, utf8_decode($solicitante), 1, 1, 'C');
$pdf->Ln(8);


// Texto informativo
$pdf->SetFont('Arial','',10);
$importante = "IMPORTANTE:\n\n- La tarjeta que usted recibe es única, personal e intransferible.
\n- Todos los ingresos que usted realice con su tarjeta quedarán registrados en el sistema de control de acceso.
\n- Si usted extravía o daña su tarjeta, deberá reportarlo de inmediato a su jefe de servicio y a la unidad de control centralizado(presencial o correo: operacion.cc.hgf@redsalud.gob.cl). El costo de una nueva tarjeta es de $9.500 pesos, según consta en circular N° 17.
\n- Una vez que ingresa a un recinto con su tarjeta, debe cerrar inmediatamente la puerta para evitar fallas en el sistema.
\n- La tarjeta entregada es solo para uso personal, no podrá permitir el paso con su tarjeta a personas no autorizadas, siendo de su exclusiva responsabilidad lo anterior.";
$pdf->MultiCell(0,5,utf8_decode($importante));
$pdf->Ln(8);

$pdf->Ln(15);

// Coordenadas base
$y_firma = 220; // posición vertical en la hoja

// Firma del jefe (izquierda)
$pdf->Image('images/firma-jefe.png', 30, $y_firma, 50);
$pdf->SetXY(25, $y_firma + 25);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,6,utf8_decode("IGNACIO DOLHATZ CONTRERAS"),0,2,'C');
$pdf->SetFont('Arial','',9);
$pdf->Cell(70,5,utf8_decode("JEFE CONTROL CENTRALIZADO"),0,2,'C');
$pdf->Cell(70,5,utf8_decode("Hospital Dr. Gustavo Fricke"),0,2,'C');

// Firma del usuario (derecha)
$pdf->SetXY(125, $y_firma + 25);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(70,6,utf8_decode(strtoupper($usuario)),0,2,'C');
$pdf->SetFont('Arial','',9);
$pdf->Cell(70,5,utf8_decode("FUNCIONARIO / USUARIO"),0,2,'C');
$pdf->Cell(70,5,utf8_decode("Hospital Dr. Gustavo Fricke"),0,2,'C');

// Fecha centrada abajo
$pdf->SetY(270);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,utf8_decode("Viña del Mar, " . date('j \d\e F \d\e Y', strtotime($acta['fecha_creacion']))),0,1,'C');

$pdf->Output("I", "Acta_$codigo.pdf");
?>
