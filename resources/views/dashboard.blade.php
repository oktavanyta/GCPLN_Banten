@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
        <p class="text-sm text-gray-500">Hasil Pendataan Fasih-SM</p>
    </div>
</div>

<!-- Grafik Harian -->
<div class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 rounded-2xl shadow-lg border border-gray-100 p-6 mb-10">
    @include('partials.chart-harian')
</div>

<div id="tab-section" class="mt-4">

    <!-- TAB CONTAINER -->
    <div class="mt-6 mb-4">
        <div class="flex gap-8 border-b border-gray-200">

            <!-- Prabayar (BIRU) -->
            <a href="?tab=prabayar#tab-section"
                class="pb-2 text-sm font-medium transition relative
                {{ request('tab','prabayar') == 'prabayar'
                    ? 'text-blue-600'
                    : 'text-gray-500 hover:text-blue-600' }}">

                Prabayar

                @if(request('tab','prabayar') == 'prabayar')
                    <span class="absolute left-0 bottom-0 w-full h-0.5 bg-blue-600 rounded-full"></span>
                @endif
            </a>

            <!-- Pascabayar (HIJAU) -->
            <a href="?tab=pascabayar#tab-section"
                class="pb-2 text-sm font-medium transition relative
                {{ request('tab') == 'pascabayar'
                    ? 'text-green-600'
                    : 'text-gray-500 hover:text-green-600' }}">

                Pascabayar

                @if(request('tab') == 'pascabayar')
                    <span class="absolute left-0 bottom-0 w-full h-0.5 bg-green-600 rounded-full"></span>
                @endif
            </a>
        </div>
    </div>

    <!-- Prabayar Tab -->
    @if(request('tab', 'prabayar') == 'prabayar')
        <!-- Filter Tanggal Prabayar -->
        <div class="bg-gradient-to-br from-sky-50 via-sky-60 to-gray-50 rounded-2xl shadow border border-t border-sky-60 p-6 mb-2">
            <form method="GET" action="/" class="grid grid-cols-12 gap-4 items-end">
                <input type="hidden" name="tab" value="prabayar">

                <!-- Tanggal -->
                <div class="col-span-12 md:col-span-2">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">Tanggal</label>
                    <input type="date" name="tanggal_prabayar" 
                        value="{{ request('tanggal_prabayar', date('Y-m-d')) }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                </div>

                <!-- UPI -->
                <div class="col-span-12 md:col-span-2">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">UPI</label>
                    <select name="upi_id" id="upi_prabayar"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua UPI</option>
                        @foreach($upis as $upi)
                            <option value="{{ $upi->id }}" {{ request('upi_id') == $upi->id ? 'selected' : '' }}>
                                [{{ $upi->kode }}] {{ $upi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- UP3 -->
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">UP3</label>
                    <select name="up3_id" id="up3_prabayar"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua UP3</option>
                        @foreach($up3s as $up3)
                            <option value="{{ $up3->id }}" {{ request('up3_id') == $up3->id ? 'selected' : '' }}>
                                [{{ $up3->kode }}] {{ $up3->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ULP -->
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">ULP</label>
                    <select name="ulp_id" id="ulp_prabayar"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua ULP</option>
                        @foreach($ulps as $ulp)
                            <option value="{{ $ulp->id }}" {{ request('ulp_id') == $ulp->id ? 'selected' : '' }}>
                                [{{ $ulp->kode }}] {{ $ulp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Button -->
                <div class="col-span-12 md:col-span-2 flex gap-2 mt-2 md:mt-0">
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex-1">
                        <span class="inline-block align-middle">Filter</span>
                    </button>
                    <a href="/" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex-1 text-center">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Tabel Prabayar -->
            <div class="overflow-x-auto mb-2">
                <div class="mb-2 mt-4 flex justify-start">
                    <a href="{{ route('export.prabayar', array_merge(request()->all(), ['tanggal_prabayar' => request('tanggal_prabayar', date('Y-m-d'))])) }}" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" /></svg>
                        Excel
                    </a>
                </div>
                <table class="min-w-full text-sm rounded-2xl overflow-hidden shadow border border-blue-100 bg-white">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="p-3 text-left font-semibold text-blue-700">Timestamp</th>
                            <th class="p-3 text-left font-semibold text-blue-700">UPI</th>
                            <th class="p-3 text-left font-semibold text-blue-700">UP3</th>
                            <th class="p-3 text-left font-semibold text-blue-700">ULP</th>
                            <th class="p-3 text-right font-semibold text-blue-700">Open</th>
                            <th class="p-3 text-right font-semibold text-blue-700">Submitted</th>
                            <th class="p-3 text-right font-semibold text-blue-700">Rejected</th>
                            <th class="p-3 text-right font-semibold text-blue-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prabayar as $row)
                            @if(Str::lower($row->jenis) === 'prabayar')
                            <tr class="border-b even:bg-gray-50 hover:bg-blue-50 transition">
                                <td class="p-2 text-gray-500">{{ $row->created_at->format('d M Y, H:i') }}</td>
                                <td class="p-2">[{{ optional(optional(optional($row->ulp)->up3)->upi)->kode ?? '-' }}] {{ optional(optional(optional($row->ulp)->up3)->upi)->nama ?? '-' }}</td>
                                <td class="p-2">[{{ optional(optional($row->ulp)->up3)->kode ?? '-' }}] {{ optional(optional($row->ulp)->up3)->nama ?? '-' }}</td>
                                <td class="p-2">[{{ optional($row->ulp)->kode ?? '-' }}] {{ optional($row->ulp)->nama ?? '-' }}</td>
                                    <td class="p-2 text-yellow-600 font-bold text-right">{{ number_format($row->open, 0, ',', '.') }}</td>
                                    <td class="p-2 text-green-600 font-bold text-right">{{ number_format($row->submitted, 0, ',', '.') }}</td>
                                    <td class="p-2 text-red-600 font-bold text-right">{{ number_format($row->rejected, 0, ',', '.') }}</td>
                                    <td class="p-2 font-bold text-right">{{ number_format($row->open + $row->submitted + $row->rejected, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                    @if($prabayar->isEmpty())
                        <tr>
                            <td class="p-4 text-center" colspan="8">Belum ada data untuk ditampilkan.</td>
                        </tr>
                    @else
                        <!-- Total Prabayar -->
                        @php
                            $totalOpen = $prabayar->sum('open');
                            $totalSubmitted = $prabayar->sum('submitted');
                            $totalRejected = $prabayar->sum('rejected');
                            $totalAll = $totalOpen + $totalSubmitted + $totalRejected;
                        @endphp
                        <tr class="bg-blue-50 font-bold text-blue-700">
                            <td class="p-2 text-center" colspan="4">Total</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalOpen, 0, ',', '.') }}</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalSubmitted, 0, ',', '.') }}</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalRejected, 0, ',', '.') }}</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalAll, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </table>
                <div class="mt-4">
                    {{ $prabayar->appends(request()->all())->fragment('tab-section')->links() }}   
                </div>
            </div>
        </div>
    @endif

    <!-- Pascabayar Tab -->
    @if(request('tab') == 'pascabayar')
        <!-- Filter Tanggal Pascabayar -->
        <div class="bg-gradient-to-br from-teal-50 via-teal-60 to-gray-50 rounded-2xl shadow border border-t border-teal-60 p-6 mb-2">
            <form method="GET" action="/" class="grid grid-cols-12 gap-4 items-end">
                <input type="hidden" name="tab" value="pascabayar">

                <!-- Tanggal -->
                <div class="col-span-12 md:col-span-2">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">Tanggal</label>
                    <input type="date" name="tanggal_pascabayar" 
                        value="{{ request('tanggal_pascabayar', date('Y-m-d')) }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                </div>

                <!-- UPI -->
                <div class="col-span-12 md:col-span-2">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">UPI</label>
                    <select name="upi_id" id="upi_pascabayar"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua UPI</option>
                        @foreach($upis as $upi)
                            <option value="{{ $upi->id }}" {{ request('upi_id') == $upi->id ? 'selected' : '' }}>
                                [{{ $upi->kode }}] {{ $upi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- UP3 -->
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">UP3</label>
                    <select name="up3_id" id="up3_pascabayar"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua UP3</option>
                        @foreach($up3s as $up3)
                            <option value="{{ $up3->id }}" {{ request('up3_id') == $up3->id ? 'selected' : '' }}>
                                [{{ $up3->kode }}] {{ $up3->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- ULP -->
                <div class="col-span-12 md:col-span-3">
                    <label class="text-xs text-gray-500 font-medium mb-1 block">ULP</label>
                    <select name="ulp_id" id="ulp_pascabayar"
                        class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua ULP</option>
                        @foreach($ulps as $ulp)
                            <option value="{{ $ulp->id }}" {{ request('ulp_id') == $ulp->id ? 'selected' : '' }}>
                                [{{ $ulp->kode }}] {{ $ulp->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Button -->
                <div class="col-span-12 md:col-span-2 flex gap-2 mt-2 md:mt-0">
                    <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex-1">
                        <span class="inline-block align-middle">Filter</span>
                    </button>
                    <a href="/" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex-1 text-center">
                        Reset
                    </a>
                </div>

            </form>

            <!-- Tabel Pascabayar -->
            <div class="overflow-x-auto mb-2">
                <div class="mb-2 mt-4 flex justify-start">
                    <a href="{{ route('export.pascabayar', array_merge(request()->all(), ['tanggal_pascabayar' => request('tanggal_pascabayar', date('Y-m-d'))])) }}" class="bg-green-600 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" /></svg>
                        Excel
                    </a>
                </div>
                <table class="min-w-full text-sm rounded-2xl overflow-hidden shadow border border-green-100 bg-white">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="p-3 text-left font-semibold text-green-700">Timestamp</th>
                            <th class="p-3 text-left font-semibold text-green-700">UPI</th>
                            <th class="p-3 text-left font-semibold text-green-700">UP3</th>
                            <th class="p-3 text-left font-semibold text-green-700">ULP</th>
                            <th class="p-3 text-right font-semibold text-green-700">Open</th>
                            <th class="p-3 text-right font-semibold text-green-700">Submitted</th>
                            <th class="p-3 text-right font-semibold text-green-700">Rejected</th>
                            <th class="p-3 text-right font-semibold text-green-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pascabayar as $row)
                            <tr class="border-b even:bg-gray-50 hover:bg-green-50 transition">
                                <td class="p-2 text-gray-500">{{ $row->created_at->format('d M Y, H:i') }}</td>
                                <td class="p-2">[{{ optional(optional(optional($row->ulp)->up3)->upi)->kode ?? '-' }}] {{ optional(optional(optional($row->ulp)->up3)->upi)->nama ?? '-' }}</td>
                                <td class="p-2">[{{ optional(optional($row->ulp)->up3)->kode ?? '-' }}] {{ optional(optional($row->ulp)->up3)->nama ?? '-' }}</td>
                                <td class="p-2">[{{ optional($row->ulp)->kode ?? '-' }}] {{ optional($row->ulp)->nama ?? '-' }}</td>
                                    <td class="p-2 text-yellow-600 font-bold text-right">{{ number_format($row->open, 0, ',', '.') }}</td>
                                    <td class="p-2 text-green-600 font-bold text-right">{{ number_format($row->submitted, 0, ',', '.') }}</td>
                                    <td class="p-2 text-red-600 font-bold text-right">{{ number_format($row->rejected, 0, ',', '.') }}</td>
                                    <td class="p-2 font-bold text-right">{{ number_format($row->open + $row->submitted + $row->rejected, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if($pascabayar->isEmpty())
                        <tr>
                            <td class="p-4 text-center" colspan="8">Belum ada data untuk ditampilkan.</td>
                        </tr>
                    @else
                        <!-- Total Pascabayar -->
                        @php
                            $totalOpenP = $pascabayar->sum('open');
                            $totalSubmittedP = $pascabayar->sum('submitted');
                            $totalRejectedP = $pascabayar->sum('rejected');
                            $totalAllP = $totalOpenP + $totalSubmittedP + $totalRejectedP;
                        @endphp
                        <tr class="bg-green-50 font-bold text-green-700">
                            <td class="p-2 text-center" colspan="4">Total</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalOpenP, 0, ',', '.') }}</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalSubmittedP, 0, ',', '.') }}</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalRejectedP, 0, ',', '.') }}</td>
                               <td class="p-2 font-bold text-right">{{ number_format($totalAllP, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                </table>
                <div class="mt-4">
                    {{ $pascabayar->appends(request()->all())->fragment('tab-section')->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function setupCascade(upiId, up3Id, ulpId) {
        const upi = document.getElementById(upiId);
        const up3 = document.getElementById(up3Id);
        const ulp = document.getElementById(ulpId);

        if (!upi || !up3 || !ulp) return;

        // Saat UPI berubah → reset bawahnya
        upi.addEventListener('change', function () {
            up3.value = '';
            ulp.value = '';
            this.form.submit();
        });

        // Saat UP3 berubah → reset ULP
        up3.addEventListener('change', function () {
            ulp.value = '';
            this.form.submit();
        });
    }

    // Prabayar
    setupCascade('upi_prabayar', 'up3_prabayar', 'ulp_prabayar');

    // Pascabayar
    setupCascade('upi_pascabayar', 'up3_pascabayar', 'ulp_pascabayar');
</script>
@endsection
