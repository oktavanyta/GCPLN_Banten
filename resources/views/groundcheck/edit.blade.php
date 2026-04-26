@extends('layouts.app')

@section('title', 'Ubah Data')

@section('content')

<div class="max-w-full mx-auto px-2 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Ubah Data</h1>
            <p class="text-sm text-gray-500">Ubah hasil input data groundcheck PLN</p>
        </div>
        <a href="{{ route('groundcheck.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg shadow-sm transition">
            Kembali
        </a>
    </div>

    <form action="{{ route('groundcheck.update', $data->id) }}" method="POST" class="w-full">
        @csrf
        @method('PUT')

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto w-full">
                <table class="min-w-[600px] w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="p-3 text-left w-28">Timestamp</th>
                            <th class="p-3 text-left">Jenis</th>
                            <th class="p-3 text-left">UPI</th>
                            <th class="p-3 text-left">UP3</th>
                            <th class="p-3 text-left">ULP</th>
                            <th class="p-3 text-center w-32">Open</th>
                            <th class="p-3 text-center w-32">Submitted</th>
                            <th class="p-3 text-center w-32">Rejected</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-3 w-28">{{ $data->created_at }}</td>
                            <td class="p-3 capitalize">{{ $data->jenis }}</td>
                            <td class="p-3 text-gray-700">{{ $data->ulp->up3->upi->nama ?? '-' }}</td>
                            <td class="p-3 text-gray-700">{{ $data->ulp->up3->nama ?? '-' }}</td>
                            <td class="p-3 font-medium">[{{ $data->ulp->kode }}] <span class="text-gray-500">{{ $data->ulp->nama }}</span></td>
                            <td class="p-2"><input type="text" name="open" id="input-open" value="{{ old('open', $data->open) }}" class="w-20 sm:w-32 border rounded-md p-1 text-center text-sm mx-auto block"></td>
                            <td class="p-2"><input type="text" name="submitted" id="input-submitted" value="{{ old('submitted', $data->submitted) }}" class="w-20 sm:w-32 border rounded-md p-1 text-center text-sm mx-auto block"></td>
                            <td class="p-2"><input type="text" name="rejected" id="input-rejected" value="{{ old('rejected', $data->rejected) }}" class="w-20 sm:w-32 border rounded-md p-1 text-center text-sm mx-auto block"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 flex justify-end w-full">
            <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 sm:px-6 py-2 rounded-lg shadow-sm transition w-full sm:w-auto">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function formatRibuan(angka) {
        return angka.replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    ['open', 'submitted', 'rejected'].forEach(function(field) {
        var input = document.getElementById('input-' + field);
        if (input) {
            input.addEventListener('input', function(e) {
                var cursor = input.selectionStart;
                var value = input.value.replace(/,/g, '');
                input.value = formatRibuan(value);
                input.setSelectionRange(cursor, cursor);
            });
            input.form && input.form.addEventListener('submit', function() {
                input.value = input.value.replace(/,/g, '');
            });
        }
    });
</script>
@endpush
@endsection
