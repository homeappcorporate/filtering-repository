<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;

class GreaterThan extends FilterField
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __construct(string $name, $value)
    {
        parent::__construct($name);
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return (null === $this->value) || ('' === $this->value);
    }
}
