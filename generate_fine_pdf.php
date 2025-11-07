<?php
require 'db_connect.php';
require 'vendor/autoload.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

// Get fine ID
if (!isset($_GET['fine_id'])) {
    die("Fine ID is required.");
}

$fine_id = $_GET['fine_id'];

// Fetch fine details
$stmt = $pdo->prepare("SELECT * FROM fines WHERE fine_id = ?");
$stmt->execute([$fine_id]);
$fine = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fine) {
    die("Fine not found.");
}


$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

// HTML content for PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; margin: 30px; }
        h2 { text-align: center; color: #1a73e8; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { padding: 10px; border: 1px solid #ccc; }
        th { background-color: #f5f5f5; }
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <h2>Traffic Fine Payment Receipt</h2>
    <p><strong>Fine ID:</strong> ' . htmlspecialchars($fine['fine_id']) . '</p>
    <table>
        <tr><th>Fine Type</th><td>' . htmlspecialchars($fine['fine_type']) . '</td></tr>
        <tr><th>Amount (Rs)</th><td>' . number_format($fine['fine_amount'], 2) . '</td></tr>
        <tr><th>Date</th><td>' . htmlspecialchars($fine['fine_date']) . '</td></tr>
        <tr><th>Time</th><td>' . htmlspecialchars($fine['fine_time']) . '</td></tr>
        <tr><th>Location</th><td>' . htmlspecialchars($fine['fine_location']) . '</td></tr>
        <tr><th>Weather</th><td>' . htmlspecialchars($fine['weather']) . '</td></tr>
        <tr><th>Payment Status</th><td>' . htmlspecialchars($fine['payment_status']) . '</td></tr>
        <tr><th>Due Date</th><td>' . htmlspecialchars($fine['due_date']) . '</td></tr>
        <tr><th>Remarks</th><td>' . htmlspecialchars($fine['remarks']) . '</td></tr>
    </table>
    <div class="footer">
        <p>Generated on ' . date('Y-m-d H:i:s') . '</p>
        <p>Thank you for using FineMate!</p>
    </div>
</body>
</html>
';

// Generate PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Force download
$dompdf->stream("Fine_Receipt_{$fine_id}.pdf", ["Attachment" => true]);
exit;
?>
