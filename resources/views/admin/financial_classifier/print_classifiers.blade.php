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
    @foreach($classifiers as $u)
        <tr>
            <td>{{ $u->type_name ?? '' }}</td>
            <td>{{ $u->code ?? '' }}</td>
            <td>{{ $u->name ?? '' }}</td>
            <td>{{ $u->active ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
