<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO;

class Sorting
{
    /** @var string */
    private $type;
    /**
     * @var mixed
     * @deprecated
     * @see https://homeappru.slack.com/archives/C01CGRT16DQ/p1614587134000900
     */
    private $base;
    /** @var string */
    private $direction;

    public function __construct(string $type, string $direction)
    {
        $this->type = $type;
        $this->direction = $direction;
    }

    /**
     * @deprecated
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @deprecated
     */
    public function setBase($base): void
    {
        $this->base = $base;
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
