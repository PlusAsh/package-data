<?php declare(strict_types=1);

namespace AshleyHardy\Data\Validators;

use AshleyHardy\Data\ValidatorAbstract;

class ValidateEmail extends ValidatorAbstract
{
    public function validate(string $emailAddress = null): bool
    {
        if(filter_var($emailAddress, FILTER_VALIDATE_EMAIL) === false) {
            $this->addMessage("Invalid email format.");
            return false;
        }

        return true;
    }
}