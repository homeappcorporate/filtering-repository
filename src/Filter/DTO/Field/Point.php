<?php

namespace Homeapp\Filter\DTO\Field;

class Point extends FilterField
{
    public const COORDINATES_CORRECTION = 0.00001;

    public const LAT = 'lat';
    public const LNG = 'lng';

    private float $lat;
    private float $lng;

    public function __construct(string $name, float $lat, float $lng)
    {
        parent::__construct($name);
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
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
        return [$this->lat, $this->lng];
    }
}
