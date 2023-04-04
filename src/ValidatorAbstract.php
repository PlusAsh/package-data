<?php declare(strict_types=1);

namespace AshleyHardy\Data;

abstract class ValidatorAbstract
{
    abstract public function validate(): bool;

    protected array $messages = [];

    protected function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return !empty($this->messages) ? $this->messages : ['validatorFailureUnknown'];
    }
}