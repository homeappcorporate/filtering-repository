<?php declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;


class NotEqual extends FilterField
{
    private $value;

    public function __construct(string $name, $value)
    {
        parent::__construct($name);
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return (null === $this->value) || ('' === $this->value);
    }
}
