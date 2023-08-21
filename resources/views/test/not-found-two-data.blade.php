<table>
    <thead>
        <tr>
            <th>Documento</th>
            <th>Fecha</th>
            <th>Resultados</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clients as $client)
            <tr>
                <td>{{ $client[0] }}</td>
                <td>{{ $client[1] }}</td>
                <td>No se encuentran en la base de datos</td>
            </tr>
        @endforeach
    </tbody>
</table>
