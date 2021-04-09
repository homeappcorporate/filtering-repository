<?php declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;


class NotEqualToField extends FilterField
{
    private $value;

    public function __construct(string $name, $value)
    {
        parent::__construct($name);
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return (string)$this->value;
    }

    public function isEmpty(): bool
    {
        return (null === $this->value) || ('' === $this->value);
    }
}
