<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;

class FromTo extends FilterField
{
    /**
     * @var mixed
     */
    private $from;
    /**
     * @var mixed
     */
    private $to;

    /**
     * @param string $name
     * @param mixed  $from
     * @param mixed  $to
     */
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

    /**
     * @return array
     *
     * @psalm-return array{0: mixed, 1: mixed}
     */
    public function getValue()
    {
        return [$this->from, $this->to];
    }
}
