<?php

namespace App\Dto;

class AbstractDto
{
    /**
     * Creating DTO.
     *
     * @param $data
     * @return static
     */
    public static function from($data): static
    {
        foreach (get_class_vars(static::class) as $fieldName => $fieldValue) {
            $dataMapper[$fieldName] = $data->{$fieldName} ?? $data[$fieldName] ?? null;
        }

        return new (static::class)(...$dataMapper ?? []);
    }

    /**
     * Get all vars of DTO.
     *
     * @return array
     */
    public function all(): array
    {
        foreach ($this ?? [] as $fieldName => $fieldValue) {
            $dataMapper[$fieldName] = $fieldValue;
        }

        return $dataMapper ?? [];
    }
}
