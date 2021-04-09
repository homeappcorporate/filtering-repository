<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO;

class Sorting
{
    /** @var string */
    private $type;
    /** @var string */
    private $direction;

    public function __construct(string $type, string $direction)
    {
        $this->type = $type;
        $this->direction = $direction;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): void
    {
        $this->direction = $direction;
    }
}
