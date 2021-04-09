<?php

namespace Homeapp\Filter\DTO\Field;

class Equal extends FilterField
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $name
     * @param mixed $value
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

    public function isTrue(): bool
    {
        return 'true' === $this->getValue() || true === $this->getValue();
    }

    public function isFalse(): bool
    {
        return 'false' === $this->getValue() || false === $this->getValue();
    }
}
