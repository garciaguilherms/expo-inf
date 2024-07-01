<?php

namespace App\Services;

use Google\Service\Sheets\AppendValuesResponse;
use Google\Service\Sheets\BatchUpdateValuesResponse;
use Google\Service\Sheets\ClearValuesResponse;
use Revolution\Google\Sheets\Facades\Sheets;

class GoogleSheetService
{
    protected mixed $spreadsheetId;
    protected string $projectSheetName = 'projects';
    protected string $userSheetName = 'users';
    protected string $authorSheetName = 'authors';

    public function __construct()
    {
        $this->spreadsheetId = '1H8zzCuEFcMa4o-1QbTCXhyNWXVgHeEr_fi5xVNmNSGw';
    }

    public function readSheet($sheetName): array
    {
        return Sheets::spreadsheet($this->spreadsheetId)
            ->sheet($sheetName)
            ->all();
    }

    public function writeSheet($sheetName, $values): AppendValuesResponse
    {
        return Sheets::spreadsheet($this->spreadsheetId)
            ->sheet($sheetName)
            ->append([$values]);
    }

    public function deleteSheet($sheetName): void
    {
        Sheets::spreadsheet($this->spreadsheetId)
            ->sheet($sheetName)
            ->clear();
    }

    public function updateProjectSheet($sheetName, $rowIndex, $values): void
    {
        $data = [array_values($values)];

        $range = "{$sheetName}!A2:J2";

        try {
            Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($sheetName)
                ->range($range)
                ->update($data);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public function deleteProjectSheetRow($sheetName, $rowIndex): ?ClearValuesResponse
    {
        $range = "{$sheetName}!A2:J2";
        return Sheets::spreadsheet($this->spreadsheetId)
            ->range($range)
            ->clear();
    }

    public function findRowById($sheetName, $id, $idColumn = 'A'): int|string|null
    {
        $rows = $this->readSheet($sheetName);
        foreach ($rows as $index => $row) {
            if (isset($row[0]) && $row[0] == $id) {
                return $index + 1;
            }
        }
        return null;
    }

    public function convertToAssociativeArray($data, $headers): array
    {
        $associativeArray = [];
        foreach ($data as $row) {
            $associativeArray[] = array_combine($headers, $row);
        }
        return $associativeArray;
    }

    public function writeAuthor($values): AppendValuesResponse
    {
        return $this->writeSheet('authors', $values);
    }

    public function writeSection($values): AppendValuesResponse
    {
        return $this->writeSheet('sections', $values);
    }

    public function getProjects(): array
    {
        $projects = $this->readSheet($this->projectSheetName);
        $authors = $this->readSheet($this->authorSheetName);

        $projectHeaders = array_shift($projects);
        $authorHeaders = array_shift($authors);

        $projects = $this->convertToAssociativeArray($projects, $projectHeaders);
        $authors = $this->convertToAssociativeArray($authors, $authorHeaders);

        $authorsMap = [];
        foreach ($authors as $author) {
            $authorsMap[$author['project_id']][] = $author;
        }

        foreach ($projects as &$project) {
            $projectId = $project['id'];
            if (isset($authorsMap[$projectId])) {
                $project['authors'] = $authorsMap[$projectId];
            } else {
                $project['authors'] = [];
            }
        }

        return $projects;
    }

}
