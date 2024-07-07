<?php

namespace App\Services;

use Google_Client;
use Google_Service_Sheets;
use Google\Service\Sheets\AppendValuesResponse;
use Google\Service\Sheets\ClearValuesResponse;

class GoogleSheetService
{
    protected mixed $spreadsheetId;
    protected string $projectSheetName = 'projects';
    protected string $authorSheetName = 'authors';
    protected Google_Service_Sheets $service;

    public function __construct()
    {
        $this->spreadsheetId = '1H8zzCuEFcMa4o-1QbTCXhyNWXVgHeEr_fi5xVNmNSGw';
        $this->initializeGoogleClient();
    }

    protected function initializeGoogleClient(): void
    {
        $config = config('google');

        $client = new Google_Client();
        $client->setApplicationName($config['application_name']);
        $client->setClientId($config['client_id']);
        $client->setClientSecret($config['client_secret']);
        $client->setRedirectUri($config['redirect_uri']);
        $client->setAccessType($config['access_type']);
        $client->setApprovalPrompt($config['approval_prompt']);
        $client->setScopes($config['scopes']);

        if ($config['service']['enable']) {
            $client->setAuthConfig($config['service']['file']);
        } else {
            $client->setDeveloperKey($config['developer_key']);
        }

        $this->service = new Google_Service_Sheets($client);
    }

    public function readSheet($sheetName): array
    {
        $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $sheetName);
        return $response->getValues();
    }

    public function writeSheet($sheetName, $values): AppendValuesResponse
    {
        $body = new Google_Service_Sheets_ValueRange(['values' => [$values]]);
        $params = ['valueInputOption' => 'RAW'];
        return $this->service->spreadsheets_values->append($this->spreadsheetId, $sheetName, $body, $params);
    }

    public function writeProjectSheet($sheetName, $values): AppendValuesResponse
    {
        $formattedValues = [
            [
                $values['id'],
                $values['title'],
                $values['description'],
                $values['image'],
                $values['section_id'],
                implode(', ', $values['author_id']),
                $values['created_at'],
                $values['updated_at'],
                $values['background_image'],
            ]
        ];

        return $this->writeSheet($sheetName, $formattedValues);
    }

    public function deleteSheet($sheetName): void
    {
        $this->service->spreadsheets_values->clear($this->spreadsheetId, $sheetName, new Google_Service_Sheets_ClearValuesRequest());
    }

    public function updateSheet($sheetName, $rowIndex, $values): void
    {
        $body = new Google_Service_Sheets_ValueRange(['values' => [array_values($values)]]);
        $range = "{$sheetName}!A{$rowIndex}:J{$rowIndex}";
        $params = ['valueInputOption' => 'RAW'];

        try {
            $this->service->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function deleteSheetRow($sheetName, $rowIndex): ?ClearValuesResponse
    {
        $range = "{$sheetName}!A{$rowIndex}:J{$rowIndex}";
        return $this->service->spreadsheets_values->clear($this->spreadsheetId, $range, new Google_Service_Sheets_ClearValuesRequest());
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
            return $this->readSheet($sheetName);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function getRelationsById($id, $relationSheet, $column): array
    {
        $relations = $this->readSheet($relationSheet);
        $relationHeaders = array_shift($relations);
        $relations = $this->convertToAssociativeArray($relations, $relationHeaders);

        return array_filter($relations, function ($relation) use ($id, $column) {
            return $relation[$column] == $id;
        });
    }

    public function searchSheets($term, $sheetName): array
    {
        $sheets = $this->readSheet($sheetName);
        $headers = array_shift($sheets);

        if (!empty($term)) {
            $filteredItems = array_filter($sheets, function($sheet) use ($term) {
                return isset($sheet[1]) && stripos($sheet[1], $term) !== false;
            });
        } else {
            $filteredItems = $sheets;
        }

        return $this->convertToAssociativeArray($filteredItems, $headers);
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
