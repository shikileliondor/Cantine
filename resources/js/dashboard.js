import Chart from 'chart.js/auto';

const revenusCanvas = document.getElementById('cantine-revenus-chart');
const repartitionCanvas = document.getElementById('cantine-repartition-chart');
const dashboardCharts = window.dashboardCharts ?? {};

if (revenusCanvas) {
    const gradient = revenusCanvas.getContext('2d').createLinearGradient(0, 0, 0, 240);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.5)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

    new Chart(revenusCanvas, {
        type: 'line',
        data: {
            labels: dashboardCharts?.revenus?.labels ?? [],
            datasets: [
                {
                    label: 'Recettes',
                    data: dashboardCharts?.revenus?.data ?? [],
                    borderColor: '#34d399',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#34d399',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#e2e8f0',
                    bodyColor: '#e2e8f0',
                    borderColor: '#1f2937',
                    borderWidth: 1,
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8' },
                },
                y: {
                    grid: { color: 'rgba(148, 163, 184, 0.1)' },
                    ticks: { color: '#94a3b8' },
                },
            },
        },
    });
}

if (repartitionCanvas) {
    new Chart(repartitionCanvas, {
        type: 'doughnut',
        data: {
            labels: dashboardCharts?.repartition?.labels ?? [],
            datasets: [
                {
                    data: dashboardCharts?.repartition?.data ?? [],
                    backgroundColor: ['#34d399', '#fbbf24', '#fb7185'],
                    borderWidth: 0,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#e2e8f0',
                    bodyColor: '#e2e8f0',
                    borderColor: '#1f2937',
                    borderWidth: 1,
                },
            },
        },
    });
}
