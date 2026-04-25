@extends('layouts.app')

@section('title', 'Input Data')

@section('content')

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-lg font-semibold text-gray-800">Input Data</h1>
        <p class="text-sm text-gray-500">Hasil pendataan Fasih-SM</p>
    </div>

    <a href="{{ route('groundcheck.create') }}" 
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm transition">
        + Tambah Data
    </a>
</div>

<!-- NOTIFIKASI -->
@if(session('success'))
    <div id="flash-success" class="relative bg-green-100 border border-green-200 text-green-700 px-4 py-2 rounded mb-4 flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button type="button" onclick="document.getElementById('flash-success').style.display='none'" class="ml-4 text-green-700 hover:text-green-900 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
@endif

<!-- FILTER CARD -->
<div class="bg-gray-50 border rounded-xl p-4">

    <form method="GET" class="grid grid-cols-12 gap-3 mb-6 items-end">
        
        <!-- Tanggal -->
        <div class="col-span-12 md:col-span-2">
            <label class="text-xs text-gray-400 mb-1 block">Tanggal</label>
            <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}"
                class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
        
        <!-- Jenis -->
        <div class="col-span-12 md:col-span-2">
            <label class="text-xs text-gray-400 mb-1 block">Jenis</label>
            <select name="jenis" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Jenis</option>
                <option value="prabayar" {{ request('jenis') == 'prabayar' ? 'selected' : '' }}>
                    Prabayar
                </option>
                <option value="pascabayar" {{ request('jenis') == 'pascabayar' ? 'selected' : '' }}>
                    Pascabayar
                </option>
            </select>
        </div>

        <!-- UPI -->
        <div class="col-span-12 md:col-span-2">
            <label class="text-xs text-gray-400 mb-1 block">UPI</label>
            <select id="upi" name="upi_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua UPI</option>
                @foreach($upis as $upi)
                    <option value="{{ $upi->id }}" {{ request('upi_id') == $upi->id ? 'selected' : '' }}>
                        [{{ $upi->kode }}] {{ $upi->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- UP3 (LEBAR) -->
        <div class="col-span-12 md:col-span-3">
            <label class="text-xs text-gray-400 mb-1 block">UP3</label>
            <select id="up3" name="up3_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua UP3</option>
            </select>
        </div>

        <!-- ULP (LEBAR) -->
        <div class="col-span-12 md:col-span-3">
            <label class="text-xs text-gray-400 mb-1 block">ULP</label>
            <select id="ulp" name="ulp_id" class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua ULP</option>
            </select>
        </div>

        <!-- BUTTON -->
        <div class="col-span-12 md:col-span-2 flex items-end">
            @if(request()->has('jenis') || request()->has('tanggal') || request()->has('upi_id') || request()->has('up3_id') || request()->has('ulp_id'))
                
                <a href="{{ route('groundcheck.index') }}" 
                    class="w-full h-9.5 flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-sm transition">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" 
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 4v5h.582M20 20v-5h-.581M5.11 9a7 7 0 0113.78 0M18.89 15a7 7 0 01-13.78 0"/>
                    </svg>

                    Reset
                </a>

            @else

                <button type="submit" 
                    class="w-full h-9.5 flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                    Filter
                </button>

            @endif
        </div>

    </form>

    <!-- TABEL -->
    <style>
        .table-mini {
            font-size: 14px;
        }
        .table-mini th, .table-mini td {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        .table-mini th.timestamp-col, .table-mini td.timestamp-col {
            min-width: 160px;
            width: 180px;
            max-width: 220px;
        }
    </style>
    <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
        <table class="w-full table-mini">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="timestamp-col">Timestamp</th>
                    <th>Jenis</th>
                    <th>UPI</th>
                    <th>UP3</th>
                    <th>ULP</th>
                    <th class="text-center">Open</th>
                    <th class="text-center">Submitted</th>
                    <th class="text-center">Rejected</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="timestamp-col">{{ is_numeric($row->created_at) ? number_format($row->created_at) : $row->created_at }}</td>
                    <td class="capitalize">{{ $row->jenis }}</td>
                    <td>
                        [{{ $row->ulp->up3->upi->kode }}] 
                        <span class="text-gray-500">{{ $row->ulp->up3->upi->nama }}</span>
                    </td>
                    <td>
                        [{{ $row->ulp->up3->kode }}] 
                        <span class="text-gray-500">{{ $row->ulp->up3->nama }}</span>
                    </td>
                    <td class="font-medium">
                        [{{ $row->ulp->kode }}] 
                        <span class="text-gray-500">{{ $row->ulp->nama }}</span>
                    </td>
                    <td class="text-center text-yellow-600 font-semibold">
                        {{ number_format($row->open, 0, ',', '.') }}
                    </td>
                    <td class="text-center text-green-600 font-semibold">
                        {{ number_format($row->submitted, 0, ',', '.') }}
                    </td>
                    <td class="text-center text-red-600 font-semibold">
                        {{ number_format($row->rejected, 0, ',', '.') }}
                    </td>
                    <td class="text-center flex justify-center gap-0">
                        <a href="{{ route('groundcheck.edit', $row->id) }}" 
                            class="text-yellow-600 hover:bg-yellow-100 p-2 rounded transition" title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.213l-4 1 1-4 12.362-12.726z" />
                            </svg>
                        </a>
                        <form action="{{ route('groundcheck.destroy', $row->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:bg-red-100 p-2 rounded transition" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m5 0H4" />
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $data->links() }}
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const upi = document.getElementById('upi');
        const up3 = document.getElementById('up3');
        const ulp = document.getElementById('ulp');

        // 🔹 Load UP3
        function loadUp3(upi_id, selectedUp3 = null) {
            if (!upi_id) return;

            fetch(`/get-up3/${upi_id}`)
                .then(res => res.json())
                .then(data => {
                    up3.innerHTML = '<option value="">Semua UP3</option>';

                    data.forEach(item => {
                        let selected = selectedUp3 == item.id ? 'selected' : '';
                        up3.innerHTML += `<option value="${item.id}" ${selected}>
                            ${item.kode} - ${item.nama}
                        </option>`;
                    });

                    // lanjut load ULP jika ada
                    if (selectedUp3) {
                        loadUlp(selectedUp3, selectedUlp);
                    }
                });
        }

        // 🔹 Load ULP
        function loadUlp(up3_id, selectedUlp = null) {
            if (!up3_id) return;

            fetch(`/get-ulp/${up3_id}`)
                .then(res => res.json())
                .then(data => {
                    ulp.innerHTML = '<option value="">Semua ULP</option>';

                    data.forEach(item => {
                        let selected = selectedUlp == item.id ? 'selected' : '';
                        ulp.innerHTML += `<option value="${item.id}" ${selected}>
                            ${item.kode} - ${item.nama}
                        </option>`;
                    });
                });
        }

        // ON CHANGE
        upi.addEventListener('change', function() {
            loadUp3(this.value);
            ulp.innerHTML = '<option value="">Semua ULP</option>';
        });

        up3.addEventListener('change', function() {
            loadUlp(this.value);
        });

        // AUTO LOAD SAAT HALAMAN DIBUKA
        if (selectedUpi) {
            upi.value = selectedUpi;
            loadUp3(selectedUpi, selectedUp3);
        }

    });

    setTimeout(function() {
        var el = document.getElementById('flash-success');
        if (el) el.style.display = 'none';
    }, 3000);
</script>
@endsection