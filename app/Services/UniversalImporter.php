<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UniversalImporter
{
    /**
     * Import data from Excel file
     *
     * @param string $filePath Absolute path to the uploaded file
     * @param string $modelClass Fully qualified Model class name
     * @param array $columnMapping Mapping of ['db_column' => excel_column_index] (1-based index)
     * @param array $defaults Default values for specific columns ['column' => 'value']
     * @return array Result summary ['total' => int, 'success' => int, 'failed' => int, 'errors' => array]
     */
    public function import(string $filePath, string $modelClass, array $columnMapping, array $defaults = [])
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        $summary = [
            'total' => 0,
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        // Start from row 2 (assuming row 1 is header)
        for ($row = 2; $row <= $highestRow; $row++) {
            $summary['total']++;

            try {
                $data = [];
                $isEmptyRow = true;

                foreach ($columnMapping as $dbColumn => $columnIndex) {
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                    $cell = $worksheet->getCell($columnLetter . $row);
                    $cellValue = $cell->getValue();

                    // Basic formatting/cleaning
                    if ($cellValue !== null && $cellValue !== '') {
                        $isEmptyRow = false;
                    }

                    // Handle Date columns if needed (PhpSpreadsheet returns float for dates)
                    if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                        $dateValue = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cellValue);
                        $data[$dbColumn] = $dateValue->format('Y-m-d');
                    } else {
                        // Handle "$[2]" replacement logic seen in legacy code
                        if (is_string($cellValue)) {
                            $cellValue = str_replace('$[2]', ' ', $cellValue);
                        }
                        $data[$dbColumn] = $cellValue;
                    }
                }

                // Skip if row is completely empty
                if ($isEmptyRow) {
                    $summary['total']--; // Don't count empty rows
                    continue;
                }

                // Merge defaults
                $data = array_merge($data, $defaults);

                // Add Audit Trails
                $data['created_by'] = Auth::id();
                $data['updated_by'] = Auth::id();

                // Create Model
                $modelClass::create($data);
                $summary['success']++;

            } catch (\Exception $e) {
                $summary['failed']++;
                $summary['errors'][] = "Row {$row}: " . $e->getMessage();
            }
        }

        return $summary;
    }
}
