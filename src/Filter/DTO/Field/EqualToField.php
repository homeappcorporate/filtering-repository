<?php declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;


class EqualToField extends FilterField
{
    private string $value;

    public function __construct(string $name, string $value)
    {
        parent::__construct($name);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return '' === $this->value;
    }
}
