<?php

namespace Tests\Unit;

use App\Services\GoogleSheetsService;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GoogleSheetsServiceTest extends TestCase
{
    protected MockObject$mockSheetsService;
    protected GoogleSheetsService $googleSheetsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockSheetsService = $this->createMock(\Google_Service_Sheets::class);
        $mockSheetsValues = $this->createMock(\Google_Service_Sheets_Resource_SpreadsheetsValues::class);

        $fakeValues = new \Google_Service_Sheets_ValueRange();
        $fakeValues->setValues([
            ['date', 'product', 'price', 'amount'],
            ['2024-02-10', 'Apple', '1.50', '10'],
            ['2024-02-11', 'Banana', '0.90', '20']
        ]);

        $mockSheetsValues->method('get')->willReturn($fakeValues);
        $this->mockSheetsService->spreadsheets_values = $mockSheetsValues;

        $mockClient = $this->createMock(\Google_Client::class);
        $fakePath = '/credentials.json';
        $this->googleSheetsService = new GoogleSheetsService(
            'testSpreadSheetId',
            $fakePath,
            $mockClient,
        );
        $this->googleSheetsService->service = $this->mockSheetsService;
    }

    public function testGetSheetRowMapping()
    {
        $result = $this->googleSheetsService->getSheetDataMapping('SalesData');

        $expected = [
            ['date' => '2024-02-10', 'product' => 'Apple', 'price' => '1.50', 'amount' => '10'],
            ['date' => '2024-02-11', 'product' => 'Banana', 'price' => '0.90', 'amount' => '20'],
        ];

        $this->assertEquals($expected, $result);
    }
}
