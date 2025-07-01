<!DOCTYPE html>
<html>
<head>
    <title>Clasificadores - Imprimir</title>
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
<h3>Listado de Clasificadores</h3>
<table>
    <thead>
    <tr>
        <th class="text-center align-middle">Tipo</th>
        <th class="text-center align-middle">Código</th>
        <th class="text-center align-middle">Nombre</th>
        <th class="text-center align-middle">Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($classifiers as $classifier)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $classifier->type_name }}</td>
            <td>{{ $classifier->code }}</td>
            <td>{{ $classifier->name }}</td>
            <td class="text-center">
          <span class="badge bg-{{ $classifier->active ? 'success' : 'danger' }}">
            {{ $classifier->active ? 'Activo' : 'Inactivo' }}
          </span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
