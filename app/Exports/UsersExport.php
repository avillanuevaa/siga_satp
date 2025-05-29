<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with(['person', 'role'])
            ->get()
            ->map(fn($u) => [
                $u->id,
                $u->person->document_number ?? '',
                $u->username,
                $u->person->name ?? '',
                $u->person->lastname ?? '',
                $u->email,
                $u->person->phone ?? '',
                $u->role->description ?? $u->role->name ?? '',
                $u->created_at->format('Y-m-d H:i'),
            ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'DNI',
            'Usuario',
            'Nombres',
            'Apellidos',
            'Email',
            'Teléfono',
            'Rol',
            'Creado',
        ];
    }
}
