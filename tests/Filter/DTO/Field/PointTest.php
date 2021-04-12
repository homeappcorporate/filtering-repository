<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\Point;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class PointTest extends TestCase
{
    public function testGetName()
    {
        $fieldName = 'fieldName';
        $point = new Point($fieldName, 0.0, 0.0);

        self::assertEquals($fieldName, $point->getName());
    }

    public function testIsEmpty()
    {
        $point = new Point('fieldName', 0.0, 0.0);

        self::assertFalse($point->isEmpty());
    }

    /**
     * @dataProvider values
     */
    public function testGetValue(string $name, float $lat, float $lng)
    {
        $point = new Point($name, $lat, $lng);

        self::assertEquals([$lat, $lng], $point->getValue());
    }

    /**
     * @dataProvider values
     */
    public function testGetLng(string $name, float $lat, float $lng)
    {
        $point = new Point($name, $lat, $lng);

        self::assertEquals($lng, $point->getLng());
    }

    /**
     * @dataProvider values
     */
    public function testGetLat(string $name, float $lat, float $lng)
    {
        $point = new Point($name, $lat, $lng);

        self::assertEquals($lat, $point->getLat());
    }

    public function values(): array
    {
        return [
            ['name' => 'test', 'lat' => 0.3, 'lng' => 0.5],
        ];
    }
}
