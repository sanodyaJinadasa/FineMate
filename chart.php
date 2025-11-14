<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Load data from JSON files
$weekly_data = json_decode(file_get_contents("data/weekly_noise.json"), true);
$hourly_data = json_decode(file_get_contents("data/hourly_noise.json"), true);

// Check if data is loaded correctly
if (!$weekly_data || !$hourly_data) {
  die("Error: Unable to load noise data. Please check if JSON files exist.");
}
?>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>


    .page-header {
      text-align: center;
      color: white;
      margin-bottom: 40px;
      padding: 20px;
    }

    .page-header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .page-header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    .charts-container {
      max-width: 1200px;
      margin: 0 auto;
    }

    .chart-box {
      background: white;
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .chart-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }

    .chart-title {
      text-align: center;
      font-size: 1.5rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 3px solid #667eea;
    }

    .chart-wrapper {
      position: relative;
      height: 350px;
      width: 100%;
    }

    canvas {
      width: 100% !important;
      height: 100% !important;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid #f0f0f0;
    }

    .stat-item {
      text-align: center;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 10px;
    }

    .stat-label {
      font-size: 0.9rem;
      color: #666;
      margin-bottom: 5px;
    }

    .stat-value {
      font-size: 1.5rem;
      font-weight: bold;
      color: #667eea;
    }

    .back-button {
      display: inline-block;
      padding: 12px 30px;
      background: white;
      color: #667eea;
      text-decoration: none;
      border-radius: 25px;
      font-weight: 600;
      margin: 20px auto;
      display: block;
      width: fit-content;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    .back-button:hover {
      background: #667eea;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 2rem;
      }

      .chart-wrapper {
        height: 280px;
      }

      .chart-box {
        padding: 15px;
      }
    }
  </style>


  <div class="page-header">
    <h1>Noise Level Analytics</h1>
  </div>

  <div class="charts-container">
    
    <!-- Weekly Noise Chart -->
    <div class="chart-box">
      <h3 class="chart-title">📊 Weekly Noise Distribution</h3>
      <div class="chart-wrapper">
        <canvas id="weeklyChart"></canvas>
      </div>
      
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-label">Average</div>
          <div class="stat-value" id="weeklyAvg">-</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">Peak Day</div>
          <div class="stat-value" id="weeklyPeak">-</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">Lowest Day</div>
          <div class="stat-value" id="weeklyLow">-</div>
        </div>
      </div>
    </div>

    <!-- Hourly Noise Chart -->
    <div class="chart-box">
      <h3 class="chart-title">📈 Hourly Noise Distribution</h3>
      <div class="chart-wrapper">
        <canvas id="hourlyChart"></canvas>
      </div>
      
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-label">Average</div>
          <div class="stat-value" id="hourlyAvg">-</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">Peak Hour</div>
          <div class="stat-value" id="hourlyPeak">-</div>
        </div>
        <div class="stat-item">
          <div class="stat-label">Quietest Hour</div>
          <div class="stat-value" id="hourlyLow">-</div>
        </div>
      </div>
    </div>

  </div>


  <!-- Chart.js Scripts -->
  <script>
    // Data from PHP
    const weeklyLabels = <?php echo json_encode(array_keys($weekly_data)); ?>;
    const weeklyValues = <?php echo json_encode(array_values($weekly_data)); ?>;
    const hourlyLabels = <?php echo json_encode(array_keys($hourly_data)); ?>;
    const hourlyValues = <?php echo json_encode(array_values($hourly_data)); ?>;

    // Calculate statistics
    function calculateStats(values) {
      const avg = (values.reduce((a, b) => a + b, 0) / values.length).toFixed(1);
      const max = Math.max(...values);
      const min = Math.min(...values);
      return { avg, max, min };
    }

    // Weekly statistics
    const weeklyStats = calculateStats(weeklyValues);
    const weeklyMaxIndex = weeklyValues.indexOf(weeklyStats.max);
    const weeklyMinIndex = weeklyValues.indexOf(weeklyStats.min);
    
    document.getElementById('weeklyAvg').textContent = weeklyStats.avg + ' dB';
    document.getElementById('weeklyPeak').textContent = weeklyLabels[weeklyMaxIndex];
    document.getElementById('weeklyLow').textContent = weeklyLabels[weeklyMinIndex];

    // Hourly statistics
    const hourlyStats = calculateStats(hourlyValues);
    const hourlyMaxIndex = hourlyValues.indexOf(hourlyStats.max);
    const hourlyMinIndex = hourlyValues.indexOf(hourlyStats.min);
    
    document.getElementById('hourlyAvg').textContent = hourlyStats.avg + ' dB';
    document.getElementById('hourlyPeak').textContent = hourlyLabels[hourlyMaxIndex];
    document.getElementById('hourlyLow').textContent = hourlyLabels[hourlyMinIndex];

    // Weekly Bar Chart
    new Chart(document.getElementById('weeklyChart'), {
      type: 'bar',
      data: {
        labels: weeklyLabels,
        datasets: [{
          label: 'Noise Level (dB)',
          data: weeklyValues,
          backgroundColor: 'rgba(102, 126, 234, 0.6)',
          borderColor: 'rgba(102, 126, 234, 1)',
          borderWidth: 2,
          borderRadius: 8,
          hoverBackgroundColor: 'rgba(102, 126, 234, 0.8)',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              font: { size: 13, weight: '600' },
              padding: 15
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            titleFont: { size: 14 },
            bodyFont: { size: 13 },
            callbacks: {
              label: function(context) {
                return 'Noise: ' + context.parsed.y + ' dB';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)',
              drawBorder: false
            },
            ticks: {
              font: { size: 12 },
              callback: function(value) {
                return value + ' dB';
              }
            }
          },
          x: {
            grid: { display: false },
            ticks: {
              font: { size: 12, weight: '500' }
            }
          }
        }
      }
    });

    // Hourly Line Chart
    new Chart(document.getElementById('hourlyChart'), {
      type: 'line',
      data: {
        labels: hourlyLabels,
        datasets: [{
          label: 'Noise Level (dB)',
          data: hourlyValues,
          fill: true,
          tension: 0.4,
          borderWidth: 3,
          borderColor: 'rgba(255, 99, 132, 1)',
          backgroundColor: 'rgba(255, 99, 132, 0.1)',
          pointRadius: 4,
          pointBackgroundColor: 'white',
          pointBorderColor: 'rgba(255, 99, 132, 1)',
          pointBorderWidth: 2,
          pointHoverRadius: 6,
          pointHoverBackgroundColor: 'rgba(255, 99, 132, 1)',
          pointHoverBorderColor: 'white',
          pointHoverBorderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: true,
            position: 'top',
            labels: {
              font: { size: 13, weight: '600' },
              padding: 15
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            titleFont: { size: 14 },
            bodyFont: { size: 13 },
            callbacks: {
              label: function(context) {
                return 'Noise: ' + context.parsed.y + ' dB';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(0, 0, 0, 0.05)',
              drawBorder: false
            },
            ticks: {
              font: { size: 12 },
              callback: function(value) {
                return value + ' dB';
              }
            }
          },
          x: {
            grid: { display: false },
            ticks: {
              font: { size: 12, weight: '500' }
            }
          }
        }
      }
    });
  </script>
