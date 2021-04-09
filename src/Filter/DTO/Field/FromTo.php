<?php

namespace Homeapp\Filter\DTO\Field;

class FromTo extends FilterField
{
    private $from;
    private $to;

    public function __construct(string $name, $from, $to)
    {
        parent::__construct($name);
        $this->from = $from;
        $this->to = $to;
    }

    public static function fromArray(string $name, array $values): FromTo
    {
        return new self($name, $values['from'] ?? null, $values['to'] ?? null);
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    public function isEmpty(): bool
    {
        return $this->from === null && $this->to === null;
    }

    public function getValue()
    {
        return [$this->from, $this->to];
    }
}
