<div class="report-wrapper">
    {!! $content !!}

    @if(isset($employees) && $employees->count())
        <hr>
        <h4 class="mb-2">Daftar Karyawan</h4>
        <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background:#f3f3f3;">
                    <th align="left">Nama</th>
                    <th align="left">Email</th>
                    <th align="left">Role</th>
                    <th align="left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $u)
                <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->getRoleNames()->first() }}</td>
                    <td>{{ optional($u->created_at)->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
