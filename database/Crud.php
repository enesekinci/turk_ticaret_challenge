<?php

namespace Database;

use PDO;

trait Crud
{
    public static function create(array $data)
    {
        $fillableColumns = self::getFillable();
        $filteredColumns = array_filter($fillableColumns, static fn ($column) => $column !== 'id');

        $values = [];

        foreach ($filteredColumns as $column) {
            if (isset($data[$column])) {
                $values[$column] = $data[$column];
            }
        }

        $queryString  = self::instance()->insertQuery($values);

        $statement = self::instance()->connection->prepare($queryString);

        foreach ($values as $column => $value) {
            $statement->bindValue(":{$column}", $value);
        }
        $result = $statement->execute();

        if (!$result) {
            return false;
        }

        $lastInsertId = self::instance()->connection->lastInsertId();

        return self::instance()->make($data)->fillId($lastInsertId);
    }

    /**
     * @param int|array $options
     * @return bool|static
     * @throws \Exception
     * @example
     * $options = [
     * 'columns' => 'id, name, email',
     * 'where' => 'name = "John"',
     * @example
     * $options = 1
     */
    public static function find(?int $id = null, string $columns = '*', string $where = '', int $limit = 1): bool|static
    {
        if ($id) {
            $where = $where ? $where . ' AND id = ' . $id : 'id = ' . $id;
        }

        $queryString = self::instance()->selectQuery($columns, $where, '', $limit);

        $query = self::instance()->connection->query($queryString);

        if ($query->rowCount() === 0) {
            return false;
        }

        $data = $query->fetch(PDO::FETCH_ASSOC);

        $id = $data['id'];

        return self::instance()->make($data)->fillId($id);
    }

    /**
     * @param array $options
     * @return array
     * @throws \Exception
     * @example
     * $options = [
     * 'columns' => 'id, name, email',
     * 'where' => 'id = 1',
     * 'orderBy' => 'id DESC',
     * 'limit' => 10,
     * 'offset' => 0
     * ]
     */
    public static function get(string $columns = '*', string $where = '', string $orderBy = '', string $limit = '', string $offset = '')
    {
        $queryString = self::instance()->selectQuery($columns, $where, $orderBy, $limit, $offset);

        $data = self::instance()->connection->query($queryString)->fetchAll(PDO::FETCH_ASSOC);

        $items = [];

        foreach ($data as $value) {
            $items[] = self::instance()->make($value)->fillId($value['id']);
        }

        return $items;
    }

    public function update(array $data)
    {
        $queryString = $this->updateQuery($data);

        $statement = $this->connection->prepare($queryString);

        foreach ($data as $column => $value) {
            $statement->bindValue(":{$column}", $value);
        }

        return $statement->execute();
    }

    public static function delete(int $id)
    {
        $queryString = self::instance()->deleteQuery($id);

        $statement = self::instance()->connection->prepare($queryString);

        $statement->bindValue(':id', $id);

        return $statement->execute();
    }

    public function save()
    {
        if (isset($this->values['id']) && $this->values['id'] > 0) {
            return $this->update($this->values);
        }

        return $this->create($this->values);
    }
}
