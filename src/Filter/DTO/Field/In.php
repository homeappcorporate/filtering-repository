<?php

namespace Homeapp\Filter\DTO\Field;

class In extends FilterField
{
    private array $values;

    public function __construct(string $name, array $values)
    {
        parent::__construct($name);
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return $this->getValues();
    }

    public function isEmpty(): bool
    {
        return empty($this->values);
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
