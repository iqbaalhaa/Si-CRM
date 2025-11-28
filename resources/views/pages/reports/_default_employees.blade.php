<h2 style="text-align:center">Laporan Karyawan</h2>
<p>Perusahaan: <strong>{{ $company->name ?? '-' }}</strong></p>
<p>Tanggal: <strong>{{ now()->format('d M Y') }}</strong></p>
<table style="width:100%" border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $u)
        <tr>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->getRoleNames()->first() }}</td>
            <td>{{ $u->created_at?->format('d M Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
