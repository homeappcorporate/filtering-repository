<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\Point;
use Homeapp\Filter\DTO\Field\Rectangle;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RectangleTest extends TestCase
{
    public function testGetName(): void
    {
        $fieldName = 'test';
        $rectangle = new Rectangle($fieldName, 0.0, 0.0, 0.0, 0.0);

        self::assertEquals($fieldName, $rectangle->getName());
    }

    /**
     * @dataProvider exceptionValues 
     */
    public function testFromSquareArrayException(string $name, array $values, string $exceptionMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        Rectangle::fromSquareArray($name, $values);
    }

    public function testFromSquareArray(): void
    {
        $values = [
            Rectangle::TOP_LEFT => [Point::LNG => 0.1, Point::LAT => 0.1],
            Rectangle::BOTTOM_RIGHT => [Point::LAT => 0.1, Point::LNG => 0.3]
        ];
        $rectangle = Rectangle::fromSquareArray('test', $values);

        self::assertEquals(Rectangle::class, get_class($rectangle));
    }

    public function exceptionValues(): array
    {
        return [
            'top_left_lng_exception' => [
                'name' => 'test',
                'values' => [],
                'exceptionMessage' => 'No top-left lng found!'
            ],
            'top_left_lat_exception' => [
                'name' => 'test',
                'values' => [
                    Rectangle::TOP_LEFT => [Point::LNG => 0.1]
                ],
                'exceptionMessage' => 'No top-left lat found!'
            ],
            'bottom_right_lng_exception' => [
                'name' => 'test',
                'values' => [
                    Rectangle::TOP_LEFT => [Point::LNG => 0.1, Point::LAT => 0.1]
                ],
                'exceptionMessage' => 'No bottom-right lat found!'
            ],
            'bottom_right_lat_exception' => [
                'name' => 'test',
                'values' => [
                    Rectangle::TOP_LEFT => [Point::LNG => 0.1, Point::LAT => 0.1],
                    Rectangle::BOTTOM_RIGHT => [Point::LAT => 0.1]
                ],
                'exceptionMessage' => 'No bottom-right lng found!'
            ],
        ];
    }

    /**
     * @dataProvider values 
     */
    public function testGetLeft(
        string $name,
        float $left,
        float $top,
        float $right,
        float $bottom
    ) {
        $rectangle = new Rectangle($name, $left, $top, $right, $bottom);

        self::assertEquals($left, $rectangle->getLeft());
    }

    /**
     * @dataProvider values 
     */
    public function testGetTop(
        string $name,
        float $left,
        float $top,
        float $right,
        float $bottom
    ) {
        $rectangle = new Rectangle($name, $left, $top, $right, $bottom);

        self::assertEquals($top, $rectangle->getTop());
    }

    /**
     * @dataProvider values 
     */
    public function testGetRight(
        string $name,
        float $left,
        float $top,
        float $right,
        float $bottom
    ) {
        $rectangle = new Rectangle($name, $left, $top, $right, $bottom);

        self::assertEquals($right, $rectangle->getRight());
    }

    /**
     * @dataProvider values 
     */
    public function testGetBottom(
        string $name,
        float $left,
        float $top,
        float $right,
        float $bottom
    ) {
        $rectangle = new Rectangle($name, $left, $top, $right, $bottom);

        self::assertEquals($bottom, $rectangle->getBottom());
    }

    /**
     * @dataProvider values 
     */
    public function testIsEmpty(
        string $name,
        float $left,
        float $top,
        float $right,
        float $bottom
    ) {
        $rectangle = new Rectangle($name, $left, $top, $right, $bottom);

        self::assertFalse($rectangle->isEmpty());
    }

    /**
     * @dataProvider values 
     */
    public function testGetValue(
        string $name,
        float $left,
        float $top,
        float $right,
        float $bottom,
        array $expectValues
    ) {
        $rectangle = new Rectangle($name, $left, $top, $right, $bottom);

        self::assertEquals($expectValues, $rectangle->getValue());
    }

    public function values(): array
    {
        return [
            [
                'name' => 'test',
                'left' => 0.1,
                'top' => 0.1,
                'right' => 0.2,
                'bottom' => 0.3,
                'expectValues' => [0.1, 0.1, 0.3, 0.2]
            ],
        ];
    }
}
