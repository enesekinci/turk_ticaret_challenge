<?php

namespace App\Models\Traits;

use PDO;

trait QueryBuilder
{
    public static function connection()
    {
        return self::instance()->connection;
    }

    public static function getTable()
    {
        return self::instance()->table ?:
            strtolower(str_replace('App\\Models\\', '', get_called_class()));
    }

    public static function getColumnsFromDatabase()
    {
        $table = self::instance()->getTable();
        $sql = "SHOW COLUMNS FROM {$table}";
        $result = self::instance()->connection->query($sql);
        $columns = $result->fetchAll(\PDO::FETCH_COLUMN);
        return $columns;
    }

    public function getColumns()
    {
        return $this->columns ?:
            $this->columns = $this->getColumnsFromDatabase();
    }

    public static function getFillable()
    {
        return self::instance()->fillable;
    }

    /**
     * @param string $columns
     * @param string $where
     * @param string $orderBy
     * @param string $limit
     * @param string $offset
     * @return string
     * @example
     * $options = [
     * 'columns' => 'id, name, email',
     * 'where' => 'id = 1',
     * 'orderBy' => 'id DESC',
     * 'limit' => 10,
     * 'offset' => 0
     * ]
     */
    public function selectQuery(string $columns = '*', string $where = '', string $orderBy = '', string $limit = '', string $offset = ''): string
    {
        $table = self::instance()->getTable();

        $query = "SELECT {$columns} FROM {$table}";

        if ($where) {
            $query .= " WHERE {$where}";
        }

        if ($orderBy) {
            $query .= " ORDER BY {$orderBy}";
        }

        if ($limit) {
            $query .= " LIMIT {$limit}";
        }

        if ($offset) {
            $query .= " OFFSET {$offset}";
        }

        return $query;
    }

    public function insertQuery(array $data)
    {
        $fillableColumns = $this->getFillable();

        $filteredColumns = array_filter($fillableColumns, static fn ($column) => $column !== 'id');

        $values = [];

        foreach ($filteredColumns as $column) {
            if (isset($data[$column])) {
                $values[$column] = $data[$column];
            }
        }

        $query = "INSERT INTO {$this->getTable()} (";
        $query .= implode(', ', array_keys($values));
        $query .= ") VALUES (";
        $query .= implode(', ', array_map(fn ($column) => ":{$column}", array_keys($values)));
        $query .= ")";

        return $query;
    }

    public function updateQuery(array $data)
    {
        $fillableColumns = $this->getFillable();

        $filteredColumns = array_filter($fillableColumns, static fn ($column) => $column !== 'id');

        $values = [];

        foreach ($filteredColumns as $column) {
            if (isset($data[$column])) {
                $values[$column] = $data[$column];
            }
        }

        $query = "UPDATE {$this->getTable()} SET ";
        $query .= implode(', ', array_map(fn ($column) => "{$column} = :{$column}", array_keys($values)));

        $query .= " WHERE id = :id";

        return $query;
    }

    public function deleteQuery()
    {
        $query = "DELETE FROM {$this->getTable()} WHERE id = :id";
        return $query;
    }
}
