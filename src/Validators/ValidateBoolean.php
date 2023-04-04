<?php declare(strict_types=1);

namespace AshleyHardy\Data\Validators;

use AshleyHardy\Data\ValidatorAbstract;

class ValidateBoolean extends ValidatorAbstract
{
    public function validate(mixed $value = null): bool
    {
        return is_bool($value);
    }
}