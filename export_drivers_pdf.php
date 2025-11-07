<?php
require 'db_connect.php';
require_once __DIR__ . '/vendor/autoload.php'; // make sure you have dompdf installed via composer

use Dompdf\Dompdf;

$stmt = $pdo->query("
    SELECT u.name, u.email, d.license_no, d.nic, d.address, d.contact_no, d.total_points, d.status
    FROM users u
    JOIN drivers d ON u.user_id = d.user_id
    WHERE u.role = 'driver'
    ORDER BY u.created_at DESC
");
$drivers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '<h2 style="text-align:center;">All Drivers Report</h2>';
$html .= '<table border="1" cellspacing="0" cellpadding="5" width="100%">
            <tr>
                <th>#</th><th>Name</th><th>Email</th><th>License No</th>
                <th>NIC</th><th>Address</th><th>Contact</th><th>Points</th><th>Status</th>
            </tr>';
foreach ($drivers as $i => $d) {
    $html .= '<tr>
                <td>' . ($i + 1) . '</td>
                <td>' . htmlspecialchars($d['name']) . '</td>
                <td>' . htmlspecialchars($d['email']) . '</td>
                <td>' . htmlspecialchars($d['license_no']) . '</td>
                <td>' . htmlspecialchars($d['nic']) . '</td>
                <td>' . htmlspecialchars($d['address']) . '</td>
                <td>' . htmlspecialchars($d['contact_no']) . '</td>
                <td>' . htmlspecialchars($d['total_points']) . '</td>
                <td>' . htmlspecialchars($d['status']) . '</td>
              </tr>';
}
$html .= '</table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("drivers_report.pdf", ["Attachment" => true]);
?>
