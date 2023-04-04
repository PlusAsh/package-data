<?php declare(strict_types=1);

namespace AshleyHardy\Data\Validators;

use AshleyHardy\Data\ValidatorAbstract;
use AshleyHardy\Persistence\ConnectionAbstract;
use AshleyHardy\Persistence\Manager;
use AshleyHardy\Persistence\Query\QueryBuilder;

class ValidateUnique extends ValidatorAbstract
{
    private string $table;
    private string $column;
    private ConnectionAbstract $connection;

    public function __construct(string $table, string $column, ?ConnectionAbstract $connection = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->connection = ($connection !== null ? $connection : Manager::platform());
    }

    public function validate(mixed $value = null): bool
    {
        if(empty($value)) {
            $this->addMessage('This field must not be empty.');
            return false;
        }
        $query = (new QueryBuilder)->select()->column($this->column)->from($this->table)->where("`{$this->column}` = ?", $value);
        $row = $this->connection->query($query);
        if(!count($row)) return true;

        $this->addMessage('This field must be unique.');
        return false;
    }
}