<?php

namespace App\Repositories;

use App\Services\GoogleSheetService;
use Exception;
use Revolution\Google\Sheets\Facades\Sheets;

class UserRepository
{
    protected GoogleSheetService $googleSheetService;
    protected string $sheetName = 'users';

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    /**
     * @throws Exception
     */
    public function getAllUsers(): array
    {
        $values = $this->googleSheetService->readSheet($this->sheetName);
        return $this->transformData($values);
    }

    public function createUser(array $userData): void
    {
        Sheets::spreadsheet(config('google.spreadsheet_id'))->sheet('users')->append([$userData]);
    }

    public function getUserByEmail($email): ?array
    {
        $sheet = Sheets::spreadsheet(config('google.spreadsheet_id'))->sheet('users');

        $rows = $sheet->all();

        $headers = array_shift($rows);

        foreach ($rows as $row) {
            if (count($row) === count($headers)) {
                $user = array_combine($headers, $row);
                if (isset($user['email']) && $user['email'] === $email) {
                    return $user;
                }
            }
        }
        return null;
    }

    public function getUserById($id): ?array
    {
        $sheet = Sheets::spreadsheet(config('google.spreadsheet_id'))->sheet('users');

        $rows = $sheet->all();

        $headers = array_shift($rows);

        foreach ($rows as $row) {
            if (count($row) === count($headers)) {
                $user = array_combine($headers, $row);
                if (isset($user['id']) && $user['id'] === $id) {
                    return $user;
                }
            }
        }
        return null;
    }

    /**
     * @throws Exception
     */
    private function transformData($values): array
    {
        $headers = array_shift($values);

        $users = [];
        foreach ($values as $row) {
            if (count($headers) == count($row)) {
                $users[] = array_combine($headers, $row);
            } else {
                throw new Exception('Linha de dados inválida' . json_encode($row));
            }
        }

        return $users;
    }

    public function updateRememberToken($id, $token): void
    {
        $users = Sheets::spreadsheet(config('google.spreadsheet_id'))->sheet('users')->get();

        foreach ($users as &$user) {
            if ($user['id'] === $id) {
                $user['remember_token'] = $token;
                Sheets::spreadsheet(config('google.spreadsheet_id'))->sheet('users')->update([$user]);
                break;
            }
        }
    }

}
