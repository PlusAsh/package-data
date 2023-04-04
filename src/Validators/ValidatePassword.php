<?php declare(strict_types=1);

namespace AshleyHardy\Data\Validators;

use AshleyHardy\Data\ValidatorAbstract;

class ValidatePassword extends ValidatorAbstract
{
    public const PASSWORD_RULES = [
        "passwordTooShortMin15" => "/\S{15,}/",
        "passwordMustContainLowercaseCharacters" => "/[a-z]+/",
        "passwordMustContainUppercaseCharacters" => "/[A-Z]+/",
        "passwordMustContainNumbers" => "/[0-9]+/",
        "passwordMustContainSpecialCharacters" =>  "/[^a-zA-Z0-9]+/"
    ];

    public function validate(string $password = null): bool
    {
        $badPassword = false;

        foreach(self::PASSWORD_RULES as $error => $regex) {
            if(!preg_match($regex, $password)) {
                $this->addMessage($error);
                $badPassword = true;
            }
        }

        return !$badPassword;
    }
}