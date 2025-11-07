<?php
session_start();
require 'db_connect.php';
require_once __DIR__ . '/vendor/autoload.php'; // Composer autoload

use Dompdf\Dompdf;
use Dompdf\Options;

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

try {
    $stmt = $pdo->query("SELECT * FROM fines ORDER BY fine_date DESC, fine_time DESC");
    $fines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching fines: " . $e->getMessage());
}

// Initialize Dompdf
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);

// Build HTML
$html = '
<style>
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
h2 { text-align: center; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #333; padding: 6px; }
th { background-color: #f2f2f2; }
</style>

<h2>Fine Records Report</h2>
<table>
<thead>
<tr>
<th>#</th>
<th>Officer</th>
<th>Offender</th>
<th>NIC</th>
<th>License</th>
<th>Vehicle</th>
<th>Fine Type</th>
<th>Amount (Rs)</th>
<th>Date</th>
<th>Time</th>
<th>Location</th>
<th>Weather</th>
<th>Status</th>
<th>Due Date</th>
<th>Remarks</th>
</tr>
</thead>
<tbody>';

foreach ($fines as $index => $fine) {
    $html .= '<tr>
        <td>' . ($index + 1) . '</td>
        <td>' . htmlspecialchars($fine['officer_id']) . '</td>
        <td>' . htmlspecialchars($fine['offender_name']) . '</td>
        <td>' . htmlspecialchars($fine['offender_nic']) . '</td>
        <td>' . htmlspecialchars($fine['offender_license_no']) . '</td>
        <td>' . htmlspecialchars($fine['vehicle_no']) . '</td>
        <td>' . htmlspecialchars($fine['fine_type']) . '</td>
        <td>' . number_format($fine['fine_amount'], 2) . '</td>
        <td>' . htmlspecialchars($fine['fine_date']) . '</td>
        <td>' . htmlspecialchars($fine['fine_time']) . '</td>
        <td>' . htmlspecialchars($fine['fine_location']) . '</td>
        <td>' . htmlspecialchars($fine['weather']) . '</td>
        <td>' . htmlspecialchars($fine['payment_status']) . '</td>
        <td>' . htmlspecialchars($fine['due_date']) . '</td>
        <td>' . htmlspecialchars($fine['remarks']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Load HTML
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Stream PDF
$dompdf->stream("fines_report.pdf", ["Attachment" => true]);
exit;
