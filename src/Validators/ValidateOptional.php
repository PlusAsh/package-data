<?php declare(strict_types=1);

namespace AshleyHardy\Data\Validators;

use AshleyHardy\Data\ValidatorAbstract;

class ValidateOptional extends ValidatorAbstract
{
    public function validate(mixed $value = null): bool
    {
        return true; //It can be here, it can not be here... Woo!
    }
}