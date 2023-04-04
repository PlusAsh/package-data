<?php declare(strict_types=1);

namespace AshleyHardy\Data;

trait EntityHelper
{
    public static function createFromForm(Form $form, array $map = []): static
    {
        return $form->toEntity(static::class, $map);
    }
}