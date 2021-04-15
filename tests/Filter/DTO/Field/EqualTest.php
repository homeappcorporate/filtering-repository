<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\Equal;
use PHPUnit\Framework\TestCase;

class EqualTest extends TestCase
{
    /**
     * @dataProvider values
     *
     * @param mixed $value
     */
    public function testGetValue($value): void
    {
        $field = new Equal('somefield', $value);
        self::assertEquals($value, $field->getValue());
    }

    /**
     * @dataProvider values
     *
     * @param mixed $value
     * @param bool  $empty
     */
    public function testIsEmpty($value, bool $empty): void
    {
        $field = new Equal('somefield', $value);
        self::assertEquals($empty, $field->isEmpty());
    }

    /**
     * @dataProvider values
     *
     * @param mixed $value
     * @param bool  $empty
     * @param bool  $true
     */
    public function testIsTrue($value, bool $_, bool $true, bool $false): void
    {
        $field = new Equal('somefield', $value);
        self::assertEquals($true, $field->isTrue());
        self::assertEquals($false, $field->isFalse());
    }

    public function testGetName(): void
    {
        $field = new Equal('somefield', 1);
        self::assertEquals('somefield', $field->getName());
    }

    public function testWithName(): void
    {
        $field = new Equal('somefield', 1);
        $testField = $field->withName('test');
        self::assertEquals('test', $testField->getName());
    }

    public function values(): array
    {
        return [
            ['value' => 1, 'empty' => false, 'true' => false, 'false' => false],
            ['value' => '1', 'empty' => false, 'true' => false, 'false' => false],
            ['value' => [1, 2, 3], 'empty' => false, 'true' => false, 'false' => false],
            ['value' => 'true', 'empty' => false, 'true' => true, 'false' => false],
            ['value' => true, 'empty' => false, 'true' => true, 'false' => false],
            ['value' => 'false', 'empty' => false, 'true' => false, 'false' => true],
            ['value' => false, 'empty' => false, 'true' => false, 'false' => true],
            ['value' => null, 'empty' => true, 'true' => false, 'false' => false],
            ['value' => '', 'empty' => true, 'true' => false, 'false' => false],
        ];
    }
}
