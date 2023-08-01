<?php

namespace Database;

use PDO;

trait Crud
{
    public static function create(array $data)
    {
        $table = self::getTable();
        $fillableColumns = self::getFillable();
        $filteredColumns = array_filter($fillableColumns, static fn ($column) => $column !== 'id');

        $values = [];

        foreach ($filteredColumns as $column) {
            if (isset($data[$column])) {
                $values[$column] = $data[$column];
            }
        }

        $sql = "INSERT INTO {$table} (";
        $sql .= implode(', ', array_keys($values));
        $sql .= ") VALUES (";
        $sql .= implode(', ', array_map(fn ($column) => ":{$column}", array_keys($values)));
        $sql .= ")";

        $statement = self::instance()->connection->prepare($sql);

        foreach ($values as $column => $value) {
            $statement->bindValue(":{$column}", $value);
        }
        $result = $statement->execute();

        if (!$result) {
            return false;
        }

        $data['id'] = self::instance()->connection->lastInsertId();

        return self::instance()->make($data);
    }

    public static function find(int $id)
    {
        $table = self::getTable();

        $queryString = "SELECT * FROM {$table} WHERE id = {$id}";

        $query = self::instance()->connection->query($queryString);

        if ($query->rowCount() === 0) {
            return false;
        }

        $data = $query->fetch(PDO::FETCH_ASSOC);

        return self::instance()->make($data);
    }

    public function get()
    {
        #TODO: normalde where ve order by gibi sorgular burada olacak

        $query = $this->connection->query("SELECT * FROM {$this->getTable()}");

        $data = $query->fetchAll(PDO::FETCH_ASSOC);

        $users = [];

        foreach ($data as $value) {
            $className = get_called_class();

            $model = new $className();
            $user = $model->make($value);

            $users[] = $user;
        }

        return $users;
    }

    public function update(array $data)
    {
        $fillableColumns = $this->getFillable();

        foreach ($fillableColumns as $column) {
            if (isset($data[$column])) {
                $this->values[$column] = $data[$column];
            }
        }

        $sql = "UPDATE {$this->getTable()} SET ";

        $sql .= implode(', ', array_map(function ($column) {
            return "{$column} = :{$column}";
        }, $fillableColumns));

        $sql .= " WHERE id = :id";

        return  $this->connection->prepare($sql)->execute($this->values);
    }

    public static function delete(int $id)
    {
        $table = self::getTable();

        $sql = "DELETE FROM {$table} WHERE id = {$id}";

        return self::instance()->connection->exec($sql);
    }

    public function save()
    {
        if (isset($this->values['id']) && $this->values['id'] > 0) {
            return $this->update($this->values);
        }

        return $this->create($this->values);
    }
}
