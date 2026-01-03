<div class="report-wrapper">
    {!! $content !!}

    @if(isset($customers) && $customers->count())
        <hr>
        <h4 class="mb-2">Daftar Customers</h4>
        <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr style="background:#f3f3f3;">
                    <th align="left">Nama</th>
                    <th align="left">Email</th>
                    <th align="left">Telepon</th>
                    <th align="left">Sumber</th>
                    <th align="left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->phone }}</td>
                    <td>{{ $c->source }}</td>
                    <td>{{ optional($c->created_at)->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
