<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;

interface FieldInterface
{
    public function isEmpty(): bool;

    public function getName(): string;

    public function withName(string $name): self;

    /**
     * @return mixed
     */
    public function getValue();
}
