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

    public function updateSheet($sheetName, $rowIndex, $values): void
    {
        $data = [array_values($values)];

        $range = "{$sheetName}!A{$rowIndex}:J{$rowIndex}";

        try {
            Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($sheetName)
                ->range($range)
                ->update($data);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function deleteSheetRow($sheetName, $rowIndex): ?ClearValuesResponse
    {
        $range = "{$sheetName}!A{$rowIndex}:J{$rowIndex}";
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
            if (count($headers) == count($row)) {
                $associativeArray[] = array_combine($headers, $row);
            } else {
                error_log("Mismatched lengths: Headers: " . count($headers) . ", Row: " . count($row));
            }
        }

        return $associativeArray;
    }

    public function findRowIndexById($id, $sheetName): int|string|null
    {
        $sheetData = $this->fetchSheetData($sheetName);

        foreach ($sheetData as $index => $row) {
            if (isset($row[0]) && $row[0] == $id) {
                return $index + 1;
            }
        }

        return null;
    }

    public function fetchSheetData($sheetName): array
    {
        try {
            return Sheets::spreadsheet($this->spreadsheetId)
                ->sheet($sheetName)
                ->all();

        } catch (\Exception $e) {
            dd($e->getMessage());
        }
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

    public function getCommentsByProjectId($projectId): array
    {
        $comments = $this->readSheet('comments');

        $commentHeaders = array_shift($comments);

        $comments = $this->convertToAssociativeArray($comments, $commentHeaders);

        return array_filter($comments, function ($comment) use ($projectId) {
            return $comment['project_id'] == $projectId;
        });
    }

    public function getProjectsBySectionId($sectionId): array
    {
        $projects = $this->readSheet($this->projectSheetName);
        $projectHeaders = array_shift($projects);
        $projects = $this->convertToAssociativeArray($projects, $projectHeaders);

        return array_filter($projects, function ($project) use ($sectionId) {
            return $project['section_id'] === $sectionId;
        });
    }


}
