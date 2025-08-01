<table>
    <thead>
        <tr>
            <th>Documento</th>
            <th>Resultados</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($clients as $doc)
            <tr>
                <td>{{ $doc }}</td>
                <td>No se encuentran en la base de datos</td>
            </tr>
        @endforeach
    </tbody>
</table>
