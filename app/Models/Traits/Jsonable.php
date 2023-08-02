<?php

namespace App\Models\Traits;

trait Jsonable
{
    public function toJson()
    {
        $jsonable = $this->jsonable ?: $this->getFillable() ?: $this->getColumns();

        $values = $this->values ?: [];

        $data = [];

        foreach ($jsonable as $column) {
            if (isset($values[$column])) {
                $data[$column] = $values[$column];
            }
        }
        return $data;
    }

    public function toArray()
    {
        return $this->toJson();
    }
}
