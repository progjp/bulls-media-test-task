<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

readonly class ModelCreateService
{
    public function __construct(private string $tableName, private array $tableColumns)
    {
    }

    public function create(): Model
    {
        $object = new class extends Model {
        };
        $object->setTable($this->tableName);
        $object->timestamps = true;

        foreach ($this->tableColumns as $name => $column) {
            if (isset($column['is_key']) && $column['is_key'] === true) {
                $object->setKeyName($name);
            }
        }

        return $object;
    }
}
