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

    public function writeProjectSheet($sheetName, $values): AppendValuesResponse
    {
        $authorIds = is_array($values['author_id']) ? implode(',', $values['author_id']) : $values['author_id'];

        $formattedValues = [
            [
                $values['id'],
                $values['title'],
                $values['description'],
                $values['image'],
                $values['section_id'],
                $authorIds,
                $values['created_at'],
                $values['updated_at'],
                $values['background_image'],
            ]
        ];

        return Sheets::spreadsheet($this->spreadsheetId)
            ->sheet($sheetName)
            ->append($formattedValues);
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
        $headers = array_shift($rows);

        $rows = $this->convertToAssociativeArray($rows, $headers);

        foreach ($rows as $index => $row) {
            if (isset($row['id']) && $row['id'] == $id) {
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
    public function getRelationsById($id, $relationSheet, $column): array
    {
        $relations = $this->readSheet($relationSheet);
        $relationHeaders = array_shift($relations);
        $relations = $this->convertToAssociativeArray($relations, $relationHeaders);

        $filteredRelations = array_filter($relations, function ($relation) use ($id, $column) {
            return $relation[$column] == $id;
        });

        return array_values($filteredRelations);
    }
    public function getAuthorsByProjectId($projectId): array
    {
        $authors = $this->readSheet('authors');
        $headers = array_shift($authors);

        if (empty($authors)) {
            return [];
        }

        return array_filter($authors, function ($author) use ($projectId) {
            return isset($author[1]) && $author[1] == $projectId;
        });
    }

    public function searchSheets($term, $sheetName): array
    {
        $sheets = $this->readSheet($sheetName);
        $headers = array_shift($sheets);

        if (!empty($term)) {
            $filteredItems = array_filter($sheets, function($sheet) use ($term, $sheetName) {
                if ($sheetName === 'sections') {
                    return (isset($sheet[1]) && stripos($sheet[1], $term) !== false) ||
                        (isset($sheet[3]) && stripos($sheet[3], $term) !== false);
                } else {
                    return isset($sheet[1]) && stripos($sheet[1], $term) !== false;
                }
            });
        } else {
            $filteredItems = $sheets;
        }

        return $this->convertToAssociativeArray($filteredItems, $headers);
    }

    public function searchSectionsWithProjects($term): array
    {
        $sections = $this->searchSheets($term, 'sections');

        foreach ($sections as &$section) {
            $sectionId = $section['id'];
            $projects = $this->getProjectsForSection($sectionId);
            $section['projects'] = $projects;
        }

        return $sections;
    }

    public function getProjectsForSection($sectionId): array
    {
        return $this->getRelationsById($sectionId, 'projects', 'section_id');

    }
    public function getAuthorsForProject($sectionId): array
    {
        return $this->getRelationsById($sectionId, 'users', 'author_id');

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
        $users = $this->readSheet('users');
        $projectHeaders = array_shift($projects);
        $userHeaders = array_shift($users);

        $projects = $this->convertToAssociativeArray($projects, $projectHeaders);
        $users = $this->convertToAssociativeArray($users, $userHeaders);

        $usersMap = [];
        foreach ($users as $user) {
            $usersMap[$user['id']] = $user;
        }

        foreach ($projects as &$project) {
            $authorIds = explode(',', $project['author_id']);
            $project['authors'] = [];
            foreach ($authorIds as $authorId) {
                $authorId = trim($authorId);
                if (isset($usersMap[$authorId])) {
                    $project['authors'][] = $usersMap[$authorId];
                }
            }
        }

        return $projects;
    }

}
