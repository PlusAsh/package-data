<?php declare(strict_types=1);

namespace AshleyHardy\Data\Validators;

use AshleyHardy\Data\ValidatorAbstract;
use RuntimeException;

class ValidateRequired extends ValidatorAbstract
{
    public function validate(mixed $value = null): bool
    {
        if(empty($value)) {
            $this->addMessage("Field must be non-empty.");
            return false;
        }

        return true;
    }
}