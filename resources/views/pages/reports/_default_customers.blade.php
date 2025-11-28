<h2 style="text-align:center">Laporan Customers</h2>
<p>Perusahaan: <strong>{{ $company->name ?? '-' }}</strong></p>
<p>Tanggal: <strong>{{ now()->format('d M Y') }}</strong></p>
<table style="width:100%" border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Sumber</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $c)
        <tr>
            <td>{{ $c->name }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ $c->phone }}</td>
            <td>{{ $c->source }}</td>
            <td>{{ $c->created_at?->format('d M Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
