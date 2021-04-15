<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use Homeapp\Filter\DTO\Field\In;
use PHPUnit\Framework\TestCase;

class InTest extends TestCase
{
    public function testGetName(): void
    {
        $fieldName = 'fieldName';

        $in = new In($fieldName, ['test']);

        self::assertEquals($fieldName, $in->getName());
    }

    /** @dataProvider values  */
    public function testIsEmpty(array $values, bool $isEmpty): void
    {
        $in = new In('fieldName', $values);

        self::assertEquals($isEmpty, $in->isEmpty());
    }

    /** @dataProvider values  */
    public function testGetValue(array $values, bool $isEmpty): void
    {
        $in = new In('fieldName', $values);

        self::assertEquals($values, $in->getValue());
    }

    public function values(): array
    {
        return [
            'empty_array' => ['values' => [], 'isEmpty' => true,],
            'not_empty' => ['values' => [1, 2, 3, 4], 'isEmpty' => false,],
        ];
    }
}
