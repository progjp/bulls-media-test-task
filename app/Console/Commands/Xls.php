<?php

namespace App\Console\Commands;

use App\Services\DatabaseStructureService;
use App\Services\GoogleSheetsService;
use App\Services\ModelCreateService;
use Google\Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class Xls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:xls';

    protected $description = 'Processed google spreadsheets';

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $sheets = config('google_sheets.sheets');
        foreach ($sheets as $sheetData) {
            $tabs = $sheetData['tabs'];
            $sheetsService = new GoogleSheetsService($sheetData['id'], config('google_sheets.credentials_path'));
            $allowedSheets = array_column($tabs, 'name');
            $this->info('ID:' . $sheetData['id']);
            foreach ($tabs as $tab) {
                $validator = Validator::make($tab, [
                    'name' => 'required|string',
                    'db_table' => 'required|string',
                    'fields' => 'required|array|min:1',
                    'fields.*.db_column' => 'required|string',
                    'fields.*.db_type' => 'required|string|in:string,integer,decimal,datetime,text',
                    'fields.*.date_format' => 'nullable|string',
                    'fields.*.precision' => 'nullable|integer|min:1',
                    'fields.*.additional_modifiers' => 'nullable|array',
                    'fields.*.additional_modifiers.*' => 'string|in:nullable,unique,index',
                ]);
                if ($validator->fails() || !in_array($tab['name'], $allowedSheets)) {
                    $this->error('FAILED VALIDATION');
                    continue;
                }

                /** @var DatabaseStructureService $dataBaseStructureService */
                $dataBaseStructureService = App::make(DatabaseStructureService::class, [
                    'tableName' => $tab['db_table'],
                    'tableColumns' => $tab['fields'],
                ]);

                try {
                    $message = $dataBaseStructureService->checkData();
                    $this->info($message === 'create'?'New table created ' .
                        $tab['db_table']:'Update table ' . $tab['db_table']);
                }catch (\Throwable $e){
                    $this->error($e->getMessage());
                    continue;
                }

                /** @var ModelCreateService $modelCreateService */
                $modelCreateService = App::make(ModelCreateService::class, [
                    'tableName' => $tab['db_table'],
                    'tableColumns' => $tab['fields'],
                ]);
                $mappedFields = $dataBaseStructureService->getMappedFields();
                $rows = $sheetsService->getSheetDataMapping($tab['name']);
                foreach ($rows as $row) {
                    $model = $modelCreateService->create();
                    foreach ($row as $field => $value) {
                        $model->{$mappedFields[$field]['table_column']} = $value;
                    }
                    $model->save();
                }
            }
        }
    }
}
