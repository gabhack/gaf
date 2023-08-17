
<table>
    <thead>
    <tr>
        <th>Documento</th>
        <th>Pagadurias</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clients as $client)
        <tr>
            <td>{{ $client->doc }}</td>
            <td>{{ $client->pagadurias }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
