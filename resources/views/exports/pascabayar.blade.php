<table>
    <thead>
        <tr>
            <th colspan="8" style="font-size:16px; font-weight:bold; text-align:center;">
                Monitoring GC-PLN Pascabayar
            </th>
        </tr>
        <tr>
            <th colspan="8" style="font-size:11px; font-style:italic; text-align:center;">
                Timestamp Fasih-SM: {{ $timestamp }} WIB
            </th>
        </tr>
        <tr></tr>
        <tr>
            <th>Timestamp</th>
            <th>UPI</th>
            <th>UP3</th>
            <th>ULP</th>
            <th>Open</th>
            <th>Submitted</th>
            <th>Rejected</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row->created_at }}</td>
            <td>[{{ optional(optional(optional($row->ulp)->up3)->upi)->kode ?? '-' }}] {{ optional(optional(optional($row->ulp)->up3)->upi)->nama ?? '-' }}</td>
            <td>[{{ optional(optional($row->ulp)->up3)->kode ?? '-' }}] {{ optional(optional($row->ulp)->up3)->nama ?? '-' }}</td>
            <td>[{{ optional($row->ulp)->kode ?? '-' }}] {{ optional($row->ulp)->nama ?? '-' }}</td>
            <td>{{ $row->open }}</td>
            <td>{{ $row->submitted }}</td>
            <td>{{ $row->rejected }}</td>
            <td>{{ $row->open + $row->submitted + $row->rejected }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
