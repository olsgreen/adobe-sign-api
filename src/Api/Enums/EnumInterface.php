<?php

namespace Olsgreen\AdobeSign\Api\Enums;

interface EnumInterface
{
    public function all(): array;
    public function diff(array $values): array;
    public function contains($value): bool;
}