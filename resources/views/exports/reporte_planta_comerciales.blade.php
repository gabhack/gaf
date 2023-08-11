<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <!-- <th>Freelance</th> -->
            <th>Oficina</th>
            <th>Email</th>
            <th>Teléfono</th>
            <!-- <th>Login</th> -->
            <!-- <th>Jefe Comercial</th> -->
            <th>Estado</th>
            <!-- <th>Tipo Comercial</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach($reporte_planta_comerciales as $usuario)
        <tr>
            <td>{{ $usuario->nombre_comercial }}</td>
            <td>{{ $usuario->oficina }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->telefono }}</td>
            <td>{{ $usuario->estado }}</td>
        </tr>
        @endforeach
    </tbody>
</table>