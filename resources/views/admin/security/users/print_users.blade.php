<!DOCTYPE html>
<html>
<head>
    <title>Usuarios - Imprimir</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px;
        }
    </style>
</head>
<body onload="window.print()">
<h3>Listado de Usuarios</h3>
<table>
    <thead>
    <tr>
        <th>DNI</th>
        <th>Usuario</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Teléfono</th>
        <th>Rol</th>
        <th>Fecha de creación</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $u)
        <tr>
            <td>{{ $u->person->document_number ?? '' }}</td>
            <td>{{ $u->username }}</td>
            <td>{{ $u->person->name ?? '' }}</td>
            <td>{{ $u->person->lastname ?? '' }}</td>
            <td>{{ $u->person->phone ?? '' }}</td>
            <td>{{ $u->role->description ?? $u->role->name ?? '' }}</td>
            <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
