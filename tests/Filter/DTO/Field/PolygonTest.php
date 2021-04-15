<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\Polygon;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

class PolygonTest extends TestCase
{

    public function testGetName(): void
    {
        $fieldName = 'test';

        $polygon = new Polygon($fieldName, []);
        self::assertEquals($fieldName, $polygon->getName());
    }

    /**
     * @dataProvider values
     */
    public function testIsEmpty(string $name, array $values, bool $isEmpty): void
    {
        $polygon = new Polygon($name, $values);

        self::assertEquals($isEmpty, $polygon->isEmpty());
    }

    /**
     * @dataProvider values
     */
    public function testGetValue(string $name, array $values, bool $isEmpty, $returnValue): void
    {
        $polygon = new Polygon($name, $values);

        self::assertEquals($returnValue, $polygon->getValue());
    }

    /**
     * @dataProvider values
     */
    public function testGetPolygon(string $name, array $values, bool $isEmpty, $returnValue): void
    {
        $polygon = new Polygon($name, $values);

        self::assertEquals($returnValue, $polygon->getValue());
    }

    public function values(): array
    {
        return [
            'no_geometry' => [
                'name' => 'test',
                'values' => [],
                'isEmpty' => true,
                'returnValue' => []
            ],
            'geometry_is_not_array' => [
                'name' => 'test',
                'values' => ['features' => [['geometry' => 'adada']]],
                'isEmpty' => true,
                'returnValue' => []
            ],
            'geometry_is_empty_array' => [
                'name' => 'test',
                'values' => ['features' => [['geometry' => 'adada']]],
                'isEmpty' => true,
                'returnValue' => []
            ],
            'geometry_is_null' => [
                'name' => 'test',
                'values' => ['features' => [['geometry' => null]]],
                'isEmpty' => true,
                'returnValue' => []
            ],
            'geometry' => [
                'name' => 'test',
                'values' => ['features' => [['geometry' => ['1', '2']]]],
                'isEmpty' => false,
                'returnValue' => ['1', '2']
            ],
        ];
    }
}
