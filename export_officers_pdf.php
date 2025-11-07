<?php
session_start();
require 'db_connect.php';
require_once __DIR__ . '/vendor/autoload.php'; // If using Composer

use Dompdf\Dompdf;
use Dompdf\Options;

// Only admins can export
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

try {
    $stmt = $pdo->query("
        SELECT u.user_id, u.name, u.email, u.status, u.created_at, 
               o.badge_no, o.station, o.contact_no, o.rank
        FROM users u
        JOIN officers o ON u.user_id = o.user_id
        WHERE u.role = 'officer'
        ORDER BY u.created_at DESC
    ");
    $officers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching officers: " . $e->getMessage());
}

// Initialize Dompdf
$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);

// Build the HTML for PDF
$html = '
<style>
body { font-family: DejaVu Sans, sans-serif; }
h2 { text-align: center; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #333; padding: 8px; font-size: 12px; text-align: left; }
th { background-color: #f2f2f2; }
</style>

<h2>Officer List Report</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Badge No</th>
            <th>Station</th>
            <th>Contact No</th>
            <th>Rank</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>';

foreach ($officers as $index => $officer) {
    $html .= '<tr>
        <td>' . ($index + 1) . '</td>
        <td>' . htmlspecialchars($officer['name']) . '</td>
        <td>' . htmlspecialchars($officer['email']) . '</td>
        <td>' . htmlspecialchars($officer['badge_no']) . '</td>
        <td>' . htmlspecialchars($officer['station']) . '</td>
        <td>' . htmlspecialchars($officer['contact_no']) . '</td>
        <td>' . htmlspecialchars($officer['rank']) . '</td>
        <td>' . htmlspecialchars($officer['status']) . '</td>
        <td>' . htmlspecialchars($officer['created_at']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Load HTML into Dompdf
$dompdf->loadHtml($html);

// (Optional) Set paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render and stream
$dompdf->render();
$dompdf->stream("officers_report.pdf", ["Attachment" => true]);
exit;
