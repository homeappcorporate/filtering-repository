<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\FromTo;
use PHPUnit\Framework\TestCase;

class FromToTest extends TestCase
{
    /**
     * @dataProvider parseValues
     */
    public function testParseFromArray(string $fieldName, array $values, FromTo $expected): void
    {
        self::assertEquals($expected, FromTo::fromArray($fieldName, $values));
    }

    /**
     * @dataProvider parseValues
     */
    public function testGetValue(string $fieldName, array $values): void
    {
        $fromTo = new FromTo($fieldName, $values['from'], $values['to']);

        self::assertEquals(array_values($values), $fromTo->getValue());
    }

    public function testGetName()
    {
        $fromTo = new FromTo('test', 'from', 'to');

        self::assertEquals($fromTo->getName(), 'test');
    }

    /**
     * @dataProvider parseValues
     */
    public function testGetFrom(string $fieldName, array $values)
    {
        $fromTo = new FromTo($fieldName, $values['from'], $values['to']);

        self::assertEquals($values['from'], $fromTo->getFrom());
    }

    public function testIsEmpty()
    {
        $fromTo = new FromTo('test', null, null);

        self::assertTrue($fromTo->isEmpty());
    }

    /**
     * @dataProvider parseValues
     */
    public function testGetTo(string $fieldName, array $values)
    {
        $fromTo = new FromTo($fieldName, $values['from'], $values['to']);

        self::assertEquals($values['to'], $fromTo->getTo());
    }

    public function parseValues(): array
    {
        return [
            'from_to_is_null' => ['test', ['from' => null, 'to' => null], new FromTo('test', null, null)],
            'from_is_null' => ['test', ['from' => null, 'to' => 'to'], new FromTo('test', null, 'to')],
            'to_is_null' => ['test', ['from' => 'from', 'to' => null], new FromTo('test', 'from', null)],
            'all_filled' => ['test', ['from' => 'from', 'to' => 'to'], new FromTo('test', 'from', 'to')],
        ];
    }
}
