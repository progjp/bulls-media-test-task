<?php

namespace App\Services;

use Google\Exception;
use Google_Client;
use Google_Service_Sheets;

class GoogleSheetsService
{
    private Google_Client $client;
    public Google_Service_Sheets $service;

    /**
     * @throws Exception
     * @throws \Google_Exception
     */
    public function __construct(private readonly string $spreadSheetId, string $configPath, ?Google_Client $client = null)
    {
        $this->client = $client ?? new Google_Client();
        $this->client->setAuthConfig($configPath);
        $this->client->addScope(Google_Service_Sheets::SPREADSHEETS_READONLY);

        $this->service = new Google_Service_Sheets($this->client);
    }

    public function getSheetDataMapping($sheetName): array
    {
        $range = "$sheetName!A1:Z"; // Fetch first row (adjust if needed)
        $response = $this->service->spreadsheets_values->get($this->spreadSheetId, $range);

        $values = $response->getValues();
        if (empty($values)) {
            return [];
        }

        $headers = array_map('trim', $values[0]);
        $data = array_slice($values, 1);

        $mapping = [];
        foreach ($data as $row) {
            foreach ($headers as $index => $header) {
                $mappedRow[$header] = $row[$index] ?? null; // Map column name to value, set null if missing
            }
            $mapping[] = $mappedRow ?? [];
        }

        return $mapping;
    }
}
