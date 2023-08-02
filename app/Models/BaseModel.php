<?php

namespace App\Models;

use App\Models\Traits\Jsonable;
use App\Models\Traits\QueryBuilder;
use Database\Crud;
use Database\Database;
use PDO;

class BaseModel
{
    use Jsonable;
    use QueryBuilder;
    use Crud;

    protected $table;
    protected $columns;
    protected $fillable;
    protected $jsonable;
    protected $values;
    protected static $instances = [];
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

    protected static function make(array $data)
    {
        foreach (self::instance()->getColumns() as $column) {
            self::instance()->values[$column] = $data[$column];
        }

        return new static($data);
    }


    public static function instance()
    {
        $class = get_called_class();

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class;
        }

        return self::$instances[$class];
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

    protected function fillId(int $id)
    {
        $this->values['id'] = $id;
        return $this;
    }
}
