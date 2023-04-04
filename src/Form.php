<?php declare(strict_types=1);

namespace AshleyHardy\Data;

use AshleyHardy\JsonApi\Parameter;
use ReflectionClass;
use RuntimeException;

final class Form
{
    private const FIELD_UNEXPECTED = 'fieldUnexpected';
    
    private array $form;
    private bool $isValid = false;
    private array $messages = [];

    public function __construct(array|object $form)
    {
        if(is_object($form)) {
            if(method_exists($form, "toArray")) {
                $form = $form->toArray();
            } else {
                throw new RuntimeException("Object passed to Form but does not declare a toArray() method.");
            }
        }
        $this->form = $form;
    }

    public function expect(array $expectedFields): void
    {
        $this->isValid = $this->checkForUnexpectedFields(array_keys($expectedFields));
        if(!$this->isValid) return; //No point doing our other checks if we have data we're not anticipating.

        foreach($expectedFields as $fieldName => $fieldRules) {
            foreach($fieldRules as $fieldRule => $fieldRuleArgs) {
                $ruleClass = !is_numeric($fieldRule) ? $fieldRule : $fieldRuleArgs;
                $ruleArgs = is_array($fieldRuleArgs) ? $fieldRuleArgs : [];
                $this->handle($fieldName, $ruleClass, $ruleArgs);
            }
        }
    }

    private function checkForUnexpectedFields(array $expectedFieldNames): bool
    {
        foreach(array_keys($this->form) as $providedField) {
            if(!in_array($providedField, $expectedFieldNames)) {
                $this->addMessage((string) $providedField, self::FIELD_UNEXPECTED);
                return false;
            }
        }

        return true;
    }

    private function handle(string $fieldName, string $fieldRule, array $fieldRuleArguments = []): void
    {
        if(!class_exists($fieldRule)) throw new RuntimeException("Validator class $fieldRule does not exist.");

        $validator = new $fieldRule(...$fieldRuleArguments);
        if(!$validator->validate($this->form[$fieldName] ?? null)) {
            $this->isValid = false;
            $this->addMessages($fieldName, $validator->getMessages());
        }
    }

    public function get(string $field): mixed
    {
        return $this->form[$field] ?? null;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    private function addMessages(string $field, array $messages): void
    {
        if(count($messages) == 1) {
            $this->addMessage($field, $messages[0]);
            return;
        }

        $this->messages[$field] = array_merge($this->messages[$field] ?? [], $messages);
    }

    private function addMessage(string $field, string $message): void
    {
        $this->messages[$field][] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function toEntity(string $entity, array $map = []): object
    {
        $object = new $entity;
        $reflector = new ReflectionClass($object);
        $properties = $reflector->getProperties();

        foreach($properties as $property) {
            $sourceField = $property->getName();
            if(array_key_exists($sourceField, $map)) $sourceField = $map[$property->getName()];
            if($sourceField === null) continue;
            if(!array_key_exists($sourceField, $this->form)) continue;

            $property->setValue($object, $this->get($sourceField));
        }

        return $object;
    }
}