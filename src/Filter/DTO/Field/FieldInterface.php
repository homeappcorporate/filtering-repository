<?php

namespace Homeapp\Filter\DTO\Field;

interface FieldInterface
{
    public function isEmpty(): bool;

    public function getName(): string;

    public function withName(string $name): self;

    public function getValue();
}
