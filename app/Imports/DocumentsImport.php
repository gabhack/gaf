<?php

namespace App\Imports;

use App\Document;
use App\Exceptions\ImportValidationException;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Log;

class DocumentsImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {
        Log::info('Reading row from Excel file', $row);

        // Check if the row is completely empty
        if (empty($row['compania']) && empty($row['usuario']) && empty($row['cedula']) && empty($row['nombrecompleto']) && empty($row['tipo'])) {
            Log::info('Skipping empty row');
            return null;
        }

        $errors = [];

        if (empty($row['compania'])) {
            $errors[] = "Falta el valor 'compania'.";
        }

        if (empty($row['usuario'])) {
            $errors[] = "Falta el valor 'usuario'.";
        }

        if (empty($row['cedula'])) {
            $errors[] = "Falta el valor 'cedula'.";
        }

        if (empty($row['nombrecompleto'])) {
            $errors[] = "Falta el valor 'nombrecompleto'.";
        }

        if (!isset($row['tipo']) || !in_array($row['tipo'], [1, 2, 3])) {
            $errors[] = "Valor inválido o falta el valor 'tipo'. Debe ser 1, 2 o 3.";
        }

        if ($errors) {
            Log::error('Validation errors encountered', ['row' => $row, 'errors' => $errors]);
            throw new ImportValidationException($errors);
        }

        $documentTypes = [
            1 => 'certificado de defunción',
            2 => 'nacimiento',
            3 => 'historia clinica',
        ];

        $document = new Document([
            'company' => $row['compania'],
            'user' => $row['usuario'],
            'documentId' => $row['cedula'],
            'fullName' => $row['nombrecompleto'],
            'documentType' => $documentTypes[$row['tipo']],
            'status' => 'Pendiente', // Default status
            'timestamp' => now(),    // Default timestamp
        ]);

        Log::info('Document created from row', $document->toArray());

        return $document;
    }
}
