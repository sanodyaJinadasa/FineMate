<?php
ob_start(); // start output buffering to avoid unwanted output

require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Decode incoming JSON
$data = json_decode(file_get_contents('php://input'), true);
$charts = $data['charts'] ?? [];

if (empty($charts)) {
    http_response_code(400);
    echo "No chart data received.";
    exit;
}

// Build HTML for charts
$html = '
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; text-align: center; }
        h2 { color: #333; margin-bottom: 20px; }
        img { width: 90%; margin: 15px 0; border: 1px solid #ccc; border-radius: 10px; }
        .footer { font-size: 12px; color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <h2>FineMate - Charts Report</h2>
';

foreach ($charts as $i => $chart) {
    $html .= '<img src="' . htmlspecialchars($chart) . '" alt="Chart ' . ($i + 1) . '">';
}

$html .= '
    <div class="footer">Generated on ' . date('Y-m-d H:i:s') . '</div>
</body>
</html>
';

// Initialize Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Clear all previous output buffers to prevent corruption
ob_end_clean();

// Send as file
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="FineMate_Charts_Report.pdf"');
echo $dompdf->output();
exit;
