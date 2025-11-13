<?php
session_start();
require 'db_connect.php';

// Only logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// Fetch all fines
$stmt = $pdo->query("SELECT * FROM fines ORDER BY fine_date DESC, fine_time DESC");
$fines = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Chart 1: Monthly total fines ---
$stmt1 = $pdo->query("
    SELECT DATE_FORMAT(fine_date, '%Y-%m') AS month, COUNT(*) AS total_fines, SUM(fine_amount) AS total_amount
    FROM fines
    GROUP BY month
    ORDER BY month ASC
");
$monthly_data = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// --- Chart 2: Fines by type ---
$stmt2 = $pdo->query("
    SELECT fine_type, COUNT(*) AS count
    FROM fines
    GROUP BY fine_type
");
$fine_types = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// --- Chart 3: Payment status ---
$stmt3 = $pdo->query("
    SELECT payment_status, COUNT(*) AS count
    FROM fines
    GROUP BY payment_status
");
$payment_statuses = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// --- Chart 4: Fines by weather (optional interesting insight) ---
$stmt4 = $pdo->query("
    SELECT weather, COUNT(*) AS count
    FROM fines
    GROUP BY weather
");
$weather_data = $stmt4->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Fines - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="admin_dashboard.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/all_fines.css">
</head>
<body>
<?php include 'admin_header.php'; ?>
<div class="container py-5">
    <h1 class="mb-4">Fines Dashboard</h1>

    <!-- Export Button -->
    <div class="mb-4">
        <!-- <a href="export_fines_report_pdf.php" class="btn btn-success">Export as PDF</a> -->
        <button id="exportPDF" class="btn btn-danger mt-3">Export Charts as PDF</button>

    </div>

    <!-- Chart Section -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow">
                <h5 class="text-center">Monthly Total Fines</h5>
                <canvas id="chartMonthly"></canvas>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow">
                <h5 class="text-center">Fines by Type</h5>
                <canvas id="chartType"></canvas>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow">
                <h5 class="text-center">Payment Status</h5>
                <canvas id="chartPayment"></canvas>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-3 shadow">
                <h5 class="text-center">Fines by Weather</h5>
                <canvas id="chartWeather"></canvas>
            </div>
        </div>
    </div>

    <? include 'admin_footer.php'; ?>

    <script>
const monthlyLabels = <?= json_encode(array_column($monthly_data, 'month')) ?>;
const monthlyTotals = <?= json_encode(array_column($monthly_data, 'total_fines')) ?>;
const monthlyAmounts = <?= json_encode(array_column($monthly_data, 'total_amount')) ?>;

const fineTypeLabels = <?= json_encode(array_column($fine_types, 'fine_type')) ?>;
const fineTypeCounts = <?= json_encode(array_column($fine_types, 'count')) ?>;

const paymentLabels = <?= json_encode(array_column($payment_statuses, 'payment_status')) ?>;
const paymentCounts = <?= json_encode(array_column($payment_statuses, 'count')) ?>;

const weatherLabels = <?= json_encode(array_column($weather_data, 'weather')) ?>;
const weatherCounts = <?= json_encode(array_column($weather_data, 'count')) ?>;

// Chart 1: Monthly total fines (line)
new Chart(document.getElementById('chartMonthly'), {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [
            {
                label: 'Total Fines',
                data: monthlyTotals,
                borderColor: '#007bff',
                fill: false
            },
            {
                label: 'Total Amount (Rs)',
                data: monthlyAmounts,
                borderColor: '#28a745',
                fill: false
            }
        ]
    }
});

// Chart 2: Fines by type (bar)
new Chart(document.getElementById('chartType'), {
    type: 'bar',
    data: {
        labels: fineTypeLabels,
        datasets: [{
            label: 'No. of Fines',
            data: fineTypeCounts,
            backgroundColor: '#ffc107'
        }]
    }
});

// Chart 3: Payment status (pie)
new Chart(document.getElementById('chartPayment'), {
    type: 'pie',
    data: {
        labels: paymentLabels,
        datasets: [{
            data: paymentCounts,
            backgroundColor: ['#28a745', '#dc3545', '#ffc107']
        }]
    }
});

// Chart 4: Fines by weather (doughnut)
new Chart(document.getElementById('chartWeather'), {
    type: 'doughnut',
    data: {
        labels: weatherLabels,
        datasets: [{
            data: weatherCounts,
            backgroundColor: ['#007bff', '#6c757d', '#17a2b8', '#ffc107']
        }]
    }
});
</script>
<script src="admin_dashboard.js"></script>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('exportPDF').addEventListener('click', function() {
    const canvases = document.querySelectorAll('canvas');
    const images = [];

    canvases.forEach(canvas => {
        const imgData = canvas.toDataURL('image/png');
        images.push(imgData);
    });

    fetch('export_charts_pdf.php', {
        method: 'POST',
        body: JSON.stringify({ charts: images }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'FineMate_Charts_Report.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
    })
    .catch(err => alert('Error exporting PDF: ' + err));
});

</script>

</body>
</html>
