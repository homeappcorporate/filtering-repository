<?php

namespace Homeapp\Filter\DTO\Field;

class Rectangle extends FilterField
{
    const        COORDINATES_CORRECTION = 0.00001;
    public const TOP_LEFT               = 'top-left';
    public const BOTTOM_RIGHT           = 'bottom-right';

    private float $left;
    private float $top;
    private float $right;
    private float $bottom;

    public function __construct(string $name, float $left, float $top, float $right, float $bottom)
    {
        parent::__construct($name);
        $this->left = min($left, $right);
        $this->top = min($top, $bottom);
        $this->right = max($left, $right);
        $this->bottom = max($top, $bottom);
    }

    public static function fromSquareArray(string $name, array $sqr)
    {
        if (empty($sqr[self::TOP_LEFT][Point::LNG])) {
            throw new \InvalidArgumentException('No top-left lng found!');
        }
        if (empty($sqr[self::TOP_LEFT][Point::LAT])) {
            throw new \InvalidArgumentException('No top-left lat found!');
        }
        if (empty($sqr[self::BOTTOM_RIGHT][Point::LAT])) {
            throw new \InvalidArgumentException('No bottom-right lat found!');
        }
        if (empty($sqr[self::BOTTOM_RIGHT][Point::LNG])) {
            throw new \InvalidArgumentException('No bottom-right lng found!');
        }
        return new self(
            $name,
            (float)$sqr[self::TOP_LEFT][Point::LNG],
            (float)$sqr[self::TOP_LEFT][Point::LAT],
            (float)$sqr[self::BOTTOM_RIGHT][Point::LNG],
            (float)$sqr[self::BOTTOM_RIGHT][Point::LAT]
        );
    }

    public function getLeft(): float
    {
        return $this->left;
    }

    public function getTop(): float
    {
        return $this->top;
    }

    public function getRight(): float
    {
        return $this->right;
    }

    public function getBottom(): float
    {
        return $this->bottom;
    }

    public function isEmpty(): bool
    {
        return false; // can not be empty
    }

    /**
     * @return array
     */
    public function getValue()
    {
        return [$this->top, $this->left, $this->bottom, $this->right];
    }
}
