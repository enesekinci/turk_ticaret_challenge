<?php

namespace App\Models;

use App\Models\Traits\Jsonable;
use Database\Crud;
use Database\Database;
use PDO;

abstract class BaseModel
{
    use Crud;
    use Jsonable;

    protected $table;
    protected $columns;
    protected $fillable;
    protected $jsonable;
    protected $values;
    protected static $instance;
    protected PDO $connection;


    public function __construct(array $data = [])
    {
        $this->connection = Database::getInstance()->getConnection();
        if ($data) {
            $this->fill($data);
        }
    }

    public function __get($name)
    {
        if (in_array($name, $this->getColumns())) {
            return $this->values[$name];
        }

        return $this->{$name};
    }

    public function __set($name, $value)
    {
        if (in_array($name, \array_values($this->getColumns()))) {
            $this->values[$name] = $value;
            return $this;
        }

        $this->{$name} = $value;
        return $this;
    }

    public static function connection()
    {
        return self::instance()->connection;
    }

    public static function getTable()
    {
        return self::instance()->table ?:
            strtolower(str_replace('App\\Models\\', '', get_called_class()));
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

    public static function getColumnsFromDatabase()
    {
        $table = self::instance()->table;
        $sql = "SHOW COLUMNS FROM {$table}";
        $result = self::instance()->connection->query($sql);
        $columns = $result->fetchAll(\PDO::FETCH_COLUMN);
        return $columns;
    }

    public function fill(array $data)
    {
        $fillable = $this->getFillable();

        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)) {
                $this->values[$key] = $value;
            }
        }
        return $this;
    }

    protected static function make(array $data)
    {
        foreach (self::instance()->getColumns() as $column) {
            self::instance()->values[$column] = $data[$column];
        }

        return self::instance();
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new static;
        }

        return self::$instance;
    }
}
