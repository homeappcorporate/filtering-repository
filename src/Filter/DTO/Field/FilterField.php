<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;

abstract class FilterField implements FieldInterface
{
    /**
     * @var string
     */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function withName(string $name): self
    {
        $o = clone $this;
        $o->name = $name;

        return $o;
    }

    abstract public function isEmpty(): bool;
}
