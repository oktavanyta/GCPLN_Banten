<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex items-center justify-between mb-2">
    <h2 class="text-lg font-semibold text-gray-700">
        Assignment Submitted Fasih-SM
    </h2>
    <span class="text-xs text-gray-400">Last 14 days</span>
</div>

<canvas id="chartHarianGroundcheck" height="80"></canvas>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('chartHarianGroundcheck').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Prabayar',
                        data: @json($chartPrabayar),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#2563eb'
                    },
                    {
                        label: 'Pascabayar',
                        data: @json($chartPascabayar),
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.15)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: '#059669'
                    },
                    {
                        label: 'Total',
                        data: @json($chartTotal),
                        borderColor: '#ea580c',
                        backgroundColor: 'transparent',
                        borderWidth: 3,
                        borderDash: [6, 4],
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#ea580c'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    }
                }
            }
        });
    });
</script>
