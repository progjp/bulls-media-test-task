<?php

namespace App\Services;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Schema;
use ReflectionException;
use ReflectionMethod;
use Throwable;

class DatabaseStructureService
{
    private array $mappedFields = [];

    public function __construct(private string $tableName, private readonly array $tableColumns)
    {
    }

    /**
     * @throws ReflectionException
     * @throws Throwable
     */
    public function checkData(): string
    {
        $tableMethod = Schema::hasTable($this->tableName) ? 'table' : 'create';

        try {
            Schema::$tableMethod($this->tableName, function (Blueprint $table) use ($tableMethod) {
                if (!Schema::hasColumn($this->tableName, 'id')) {
                    $column = $table->integer('id')->autoIncrement()->primary();
                    $this->mappedFields['id'] = $column;
                }

                foreach ($this->tableColumns as $name => $columnData) {
                    if (!Schema::hasColumn($this->tableName, $columnData['db_column'])) {
                        $reflection = new ReflectionMethod($table, $columnData['db_type']);

                        if ($reflection->getNumberOfParameters()) {
                            $column = $table->{$columnData['db_type']}($columnData['db_column'], ...($columnData['type_params'] ?? []));
                        } else {
                            $column = $table->{$columnData['db_type']}();
                        }
                        $this->mappedFields[$name] = $column;

                        if (!empty($columnData['additional_modifiers']) && is_a($column, ColumnDefinition::class)) {
                            foreach ($columnData['additional_modifiers'] as $modifier) {
                                if (is_string($modifier) && is_callable([$column, $modifier])) {
                                    $column->$modifier();
                                } elseif (is_array($modifier)) {
                                    $method = array_splice($modifier, 0, 1);

                                    if (is_callable([$column, $method])) {
                                        $column->$method(...$modifier);
                                    }
                                }
                            }
                        }
                    }
                    $this->mappedFields[$name]['table_column'] = $columnData['db_column'];
                }
                if ($tableMethod === 'create') {
                    $table->timestamps();
                }
            });
        } catch (Throwable $e) {
            report($e);
            throw $e;
        }

        return $tableMethod;
    }

    public function getMappedFields(): array
    {
        return $this->mappedFields;
    }
}
