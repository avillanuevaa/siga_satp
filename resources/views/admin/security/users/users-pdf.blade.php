<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 12px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<h3>Listado de Usuarios</h3>
<table>
    <thead>
    <tr>
        <th>ID</th><th>DNI</th><th>Usuario</th><th>Nombres</th>
        <th>Apellidos</th><th>Email</th><th>Teléfono</th>
        <th>Rol</th><th>Creado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $u)
        <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->person->document_number ?? '' }}</td>
            <td>{{ $u->username }}</td>
            <td>{{ $u->person->name ?? '' }}</td>
            <td>{{ $u->person->lastname ?? '' }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->person->phone ?? '' }}</td>
            <td>{{ $u->role->description ?? $u->role->name ?? '' }}</td>
            <td>{{ $u->created_at->format('Y-m-d H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
