@extends('layouts.app')

@section('title', 'Tambah Data')

@section('content')

<div class="max-full mx-auto">

    <!-- 🔥 HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Tambah Data</h1>
            <p class="text-sm text-gray-500">Input pemasukan data submitted dari Fasih-SM</p>
        </div>
        <a href="{{ route('groundcheck.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg shadow-sm transition">
            Kembali
        </a>
    </div>
    <form action="{{ route('groundcheck.store') }}" method="POST" id="groundcheckForm">
        @csrf

        <!-- 🔹 FILTER CARD -->
        <div class="bg-gray-50 border rounded-xl p-4 mb-6">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- Jenis -->
                <div>
                    <label class="text-sm text-gray-600">Jenis</label>
                    <select id="jenis" name="jenis" class="w-full border rounded-lg p-2 mt-1">
                        <option value="">Pilih Jenis</option>
                        <option value="prabayar">Prabayar</option>
                        <option value="pascabayar">Pascabayar</option>
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="text-sm text-gray-600">Tanggal & Waktu</label>
                    <input id="created_at" type="datetime-local" name="created_at" 
                        class="w-full border rounded-lg p-2 mt-1">
                </div>

            </div>

        </div>

        <!-- 🔹 TABEL INPUT -->
        <div id="isianLain" class="hidden">

            <div class="bg-white border rounded-xl shadow-sm overflow-hidden">

                <div class="p-4 border-b justify-between items-center flex">
                    <!-- 🔹 BUTTON -->
                        <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-sm transition">
                            Simpan
                        </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="p-3 text-left">UPI</th>
                                <th class="p-3 text-left">UP3</th>
                                <th class="p-3 text-left">ULP</th>
                                <th class="p-3 text-center w-32">Open</th>
                                <th class="p-3 text-center w-32">Submitted</th>
                                <th class="p-3 text-center w-32">Rejected</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ulps as $ulp)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3 text-gray-700">
                                    [{{ $ulp->up3->upi->kode ?? '-' }}] {{ $ulp->up3->upi->nama ?? '-' }}
                                </td>

                                <td class="p-3 text-gray-700">
                                    [{{ $ulp->up3->kode ?? '-' }}] {{ $ulp->up3->nama ?? '-' }}
                                </td>

                                <td class="p-3 font-medium">
                                    [{{ $ulp->kode }}] 
                                    <span class="text-gray-500">{{ $ulp->nama }}</span>

                                    <input type="hidden" name="ulp_id[]" value="{{ $ulp->id }}">
                                </td>

                                <td class="p-2">
                                    <input type="number" name="open[]" 
                                        class="w-32 border rounded-md p-1 text-center text-sm mx-auto block">
                                </td>

                                <td class="p-2">
                                    <input type="number" name="submitted[]" 
                                        class="w-32 border rounded-md p-1 text-center text-sm mx-auto block">
                                </td>

                                <td class="p-2">
                                    <input type="number" name="rejected[]" 
                                        class="w-32 border rounded-md p-1 text-center text-sm mx-auto block">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </form>

</div>

<!-- 🔥 SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    const jenis = document.getElementById('jenis');
    const createdAt = document.getElementById('created_at');
    const isianLain = document.getElementById('isianLain');

    function checkIsian() {
        if (jenis.value !== '' && createdAt.value !== '') {
            isianLain.classList.remove('hidden');
        } else {
            isianLain.classList.add('hidden');
        }
    }

    jenis.addEventListener('change', checkIsian);
    createdAt.addEventListener('change', checkIsian);

});
</script>

@endsection