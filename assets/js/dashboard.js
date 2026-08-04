
document.addEventListener('DOMContentLoaded', function () {
  if (typeof Chart === 'undefined') return;

  var goldLine = '#C9A227';
  var navy = '#0F2A4A';
  var teal = '#1FA97F';
  var crimson = '#D64550';
  var gridColor = 'rgba(15,27,43,.06)';

  Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
  Chart.defaults.color = '#3A4A5E';

  
  var barCtx = document.getElementById('sectorTurnoverChart');
  if (barCtx) {
    new Chart(barCtx, {
      type: 'bar',
      data: {
        labels: ['Banking', 'Telecom', 'Manufacturing', 'Energy', 'Insurance', 'Agriculture'],
        datasets: [{
          label: 'Turnover (KES M)',
          data: [412, 356, 198, 231, 87, 64],
          backgroundColor: navy,
          borderRadius: 6,
          maxBarThickness: 42
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, grid: { color: gridColor } },
          x: { grid: { display: false } }
        }
      }
    });
  }

  var pieCtx = document.getElementById('marketCapChart');
  if (pieCtx) {
    new Chart(pieCtx, {
      type: 'doughnut',
      data: {
        labels: ['Banking', 'Telecom', 'Manufacturing', 'Energy', 'Others'],
        datasets: [{
          data: [38, 27, 14, 12, 9],
          backgroundColor: [navy, goldLine, teal, crimson, '#8C9AAE'],
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '62%',
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, padding: 14 } } }
      }
    });
  }

 
  var lineCtx = document.getElementById('nseIndexChart');
  if (lineCtx) {
    new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
        datasets: [{
          label: 'NSE 20 Share Index',
          data: [1842, 1856, 1849, 1861, 1878, 1872, 1889, 1901, 1895, 1912, 1908, 1926],
          borderColor: goldLine,
          backgroundColor: 'rgba(201,162,39,.12)',
          fill: true,
          tension: 0.35,
          pointRadius: 3,
          pointBackgroundColor: goldLine
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { grid: { color: gridColor } },
          x: { grid: { display: false }, title: { display: true, text: 'Last 12 trading sessions' } }
        }
      }
    });
  }
});
