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

        return json_encode($data);
    }
}
