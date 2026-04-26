<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ===================== -->
<!-- CHART TREND -->
<!-- ===================== -->
<div class="bg-white rounded-2xl shadow border border-gray-100 p-4 mb-4">
    <div class="flex flex-col mb-3">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-600">
                Assignment Submitted Fasih-SM
            </h3>
            <span class="text-xs text-gray-400">Last 14 days</span>
        </div>

        <!-- FILTER INFO -->
        @if($isFiltered && ($selectedUp3 || $selectedUlp))
            <div class="mt-1">
                
                @if($tab === 'prabayar')
                    <div class="inline-block bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-xs opacity-90 hover:opacity-100 transition">
                        @if($selectedUp3)
                            UP3: {{ $selectedUp3 }}
                        @endif

                        @if($selectedUlp)
                            @if($selectedUp3) • @endif
                            ULP: {{ $selectedUlp }}
                        @endif
                    </div>
                @endif

                @if($tab === 'pascabayar')
                    <div class="inline-block bg-green-50 text-green-600 px-2 py-0.5 rounded-md text-xs opacity-90 hover:opacity-100 transition">
                        @if($selectedUp3)
                            UP3: {{ $selectedUp3 }}
                        @endif

                        @if($selectedUlp)
                            @if($selectedUp3) • @endif
                            ULP: {{ $selectedUlp }}
                        @endif
                    </div>
                @endif

            </div>
        @endif

    </div>

    <canvas id="chartHarianGroundcheck" height="80"></canvas>
</div>

<!-- ===================== -->
<!-- CHART PERSENTASE -->
<!-- ===================== -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <!-- Prabayar -->
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-4">
        <h3 class="text-sm font-semibold text-gray-600">
            Komposisi Prabayar (%)
        </h3>

        @if($tab === 'prabayar' && $isFiltered && ($selectedUp3 || $selectedUlp))
            <div class="inline-block bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-xs mt-1 opacity-90 hover:opacity-100 transition">
                @if($selectedUp3)
                    UP3: {{ $selectedUp3 }}
                @endif

                @if($selectedUlp)
                    @if($selectedUp3) • @endif
                    ULP: {{ $selectedUlp }}
                @endif
            </div>
        @endif
        <canvas id="chartPrabayarStacked" height="80"></canvas>
    </div>

    <!-- Pascabayar -->
    <div class="bg-white rounded-2xl shadow border border-gray-100 p-4">
        <h3 class="text-sm font-semibold text-gray-600">
            Komposisi Pascabayar (%)
        </h3>

        @if($tab === 'pascabayar' && $isFiltered && ($selectedUp3 || $selectedUlp))
            <div class="inline-block bg-green-50 text-green-600 px-2 py-0.5 rounded-md text-xs mt-1 opacity-90 hover:opacity-100 transition">
                @if($selectedUp3)
                    UP3: {{ $selectedUp3 }}
                @endif

                @if($selectedUlp)
                    @if($selectedUp3) • @endif
                    ULP: {{ $selectedUlp }}
                @endif
            </div>
        @endif
        <canvas id="chartPascabayarStacked" height="80"></canvas>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const ctx = document.getElementById('chartHarianGroundcheck').getContext('2d');

        const datasets = [];

        const isFiltered = @json($isFiltered);
        const activeTab = @json($tab);

        // =========================
        // TANPA FILTER → tampil semua
        // =========================
        if (!isFiltered) {

            datasets.push({
                label: 'Prabayar',
                data: @json($chartPrabayar),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 3
            });

            datasets.push({
                label: 'Pascabayar',
                data: @json($chartPascabayar),
                borderColor: '#059669',
                backgroundColor: 'rgba(5, 150, 105, 0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 3
            });

            datasets.push({
                label: 'Total',
                data: @json($chartTotal),
                borderColor: '#ea580c',
                backgroundColor: 'transparent',
                borderWidth: 3,
                borderDash: [6, 4],
                tension: 0.4,
                pointRadius: 4
            });
        }

        // =========================
        // ADA FILTER → tampil 1 saja
        // =========================
        else {

            if (activeTab === 'prabayar') {
                datasets.push({
                    label: 'Prabayar',
                    data: @json($chartPrabayar),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3
                });
            }

            if (activeTab === 'pascabayar') {
                datasets.push({
                    label: 'Pascabayar',
                    data: @json($chartPascabayar),
                    borderColor: '#059669',
                    backgroundColor: 'rgba(5, 150, 105, 0.15)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3
                });
            }
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: datasets
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
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });

    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // =========================
        // DATA (PASTIKAN NUMBER)
        // =========================
        const labels = @json($chartLabels);

        const openPrabayar = @json($chartOpenPrabayar).map(Number);
        const submitPrabayar = @json($chartPrabayar).map(Number);
        const rejectedPrabayar = @json($chartRejectedPrabayar).map(Number);

        const openPascabayar = @json($chartOpenPascabayar).map(Number);
        const submitPascabayar = @json($chartPascabayar).map(Number);
        const rejectedPascabayar = @json($chartRejectedPascabayar).map(Number);

        console.log('DEBUG:', {
            openPrabayar,
            submitPrabayar,
            openPascabayar,
            submitPascabayar
        });

        // =========================
        // HELPER PERSEN
        // =========================
        function toPercentAll(openArr, submitArr, rejectedArr) {
            return openArr.map((_, i) => {
                const total = openArr[i] + submitArr[i] + rejectedArr[i];

                return {
                    open: total === 0 ? 0 : (openArr[i] / total * 100),
                    submitted: total === 0 ? 0 : (submitArr[i] / total * 100),
                    rejected: total === 0 ? 0 : (rejectedArr[i] / total * 100)
                };
            });
        }

        const prabayarPct = toPercentAll(openPrabayar, submitPrabayar, rejectedPrabayar);
        const pascabayarPct = toPercentAll(openPascabayar, submitPascabayar, rejectedPascabayar);

        const openPrabayarPct = prabayarPct.map(d => d.open);
        const submitPrabayarPct = prabayarPct.map(d => d.submitted);
        const rejectedPrabayarPct = prabayarPct.map(d => d.rejected);

        const openPascabayarPct = pascabayarPct.map(d => d.open);
        const submitPascabayarPct = pascabayarPct.map(d => d.submitted);
        const rejectedPascabayarPct = pascabayarPct.map(d => d.rejected);
        
        // =========================
        // TOOLTIP
        // =========================
        const tooltipPercent = {
            callbacks: {

                title: function(items) {
                    const i = items[0].dataIndex;
                    const isPrabayar = items[0].chart.canvas.id === 'chartPrabayarStacked';

                    const total = isPrabayar
                        ? openPrabayar[i] + submitPrabayar[i] + rejectedPrabayar[i]
                        : openPascabayar[i] + submitPascabayar[i] + rejectedPascabayar[i];

                    return 'Total: ' + total.toLocaleString('id-ID');
                },

                label: function(context) {
                    const label = context.dataset.label;
                    const percent = context.raw;
                    const i = context.dataIndex;

                    let value = 0;

                    if (context.chart.canvas.id === 'chartPrabayarStacked') {
                        if (label === 'Open') value = openPrabayar[i];
                        else if (label === 'Submitted') value = submitPrabayar[i];
                        else value = rejectedPrabayar[i];
                    } else {
                        if (label === 'Open') value = openPascabayar[i];
                        else if (label === 'Submitted') value = submitPascabayar[i];
                        else value = rejectedPascabayar[i];
                    }

                    return `${label}: ${percent.toFixed(1)}% (${value.toLocaleString('id-ID')})`;
                }
            }
        };

        // =========================
        // CHART PRABAYAR
        // =========================
        new Chart(document.getElementById('chartPrabayarStacked'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Open',
                        data: openPrabayarPct,
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        stack: 'total'
                    },
                    {
                        label: 'Submitted',
                        data: submitPrabayarPct,
                        backgroundColor: 'rgba(37, 99, 235, 0.8)',
                        stack: 'total'
                    },
                    {
                        label: 'Rejected',
                        data: rejectedPrabayarPct,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        stack: 'total'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: tooltipPercent
                },
                scales: {
                    x: { stacked: true },
                    y: {
                        stacked: true,
                        min: 0,
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });

        // =========================
        // CHART PASCABAYAR
        // =========================
        new Chart(document.getElementById('chartPascabayarStacked'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Open',
                        data: openPascabayarPct,
                        backgroundColor: 'rgba(245, 158, 11, 0.8)', // sama
                        stack: 'total'
                    },
                    {
                        label: 'Submitted',
                        data: submitPascabayarPct,
                        backgroundColor: 'rgba(37, 99, 235, 0.8)', // sama
                        stack: 'total'
                    },
                    {
                        label: 'Rejected',
                        data: rejectedPascabayarPct,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)', // sama
                        stack: 'total'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: tooltipPercent
                },
                scales: {
                    x: { stacked: true },
                    y: {
                        stacked: true,
                        min: 0,
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });

    });
</script>