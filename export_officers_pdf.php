<?php
require 'db_connect.php';
require_once 'fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'All Officers Report',0,1,'C');
        $this->Ln(5);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'#',1);
$pdf->Cell(40,10,'Name',1);
$pdf->Cell(40,10,'Email',1);
$pdf->Cell(25,10,'Badge No',1);
$pdf->Cell(25,10,'Station',1);
$pdf->Cell(25,10,'Rank',1);
$pdf->Cell(25,10,'Status',1);
$pdf->Ln();

$stmt = $pdo->query("
    SELECT u.name, u.email, u.status, o.badge_no, o.station, o.rank 
    FROM users u 
    JOIN officers o ON u.user_id = o.user_id
    WHERE u.role = 'officer'
");

$pdf->SetFont('Arial','',9);
$index = 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $pdf->Cell(10,10,$index++,1);
    $pdf->Cell(40,10,$row['name'],1);
    $pdf->Cell(40,10,$row['email'],1);
    $pdf->Cell(25,10,$row['badge_no'],1);
    $pdf->Cell(25,10,$row['station'],1);
    $pdf->Cell(25,10,$row['rank'],1);
    $pdf->Cell(25,10,$row['status'],1);
    $pdf->Ln();
}

$pdf->Output('D', 'Officers_Report.pdf');
exit;
?>
