<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use PHPUnit\Framework\TestCase;
use Homeapp\Filter\DTO\Field\EqualToField;

class EqualToFieldTest extends TestCase
{
    /**
     * @dataProvider values
     *
     * @param mixed $value
     */
    public function testGetValue($value): void
    {
        $field = new EqualToField('somefield', $value);
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
        $field = new EqualToField('somefield', $value);
        self::assertEquals($empty, $field->isEmpty());
    }

    public function testGetName(): void
    {
        $field = new EqualToField('somefield', 'test');
        self::assertEquals('somefield', $field->getName());
    }

    public function values(): array
    {
        return [
            ['value' => 'value', 'empty' => false],
            ['value' => '', 'empty' => true],
        ];
    }
}
