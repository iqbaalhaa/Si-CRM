@props([
    'headers' => [], // array th
    'rows' => [], // array of associative data
])

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                @foreach ($headers as $head)
                    <th>{{ $head }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{!! $cell !!}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" class="text-center text-muted py-3">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
