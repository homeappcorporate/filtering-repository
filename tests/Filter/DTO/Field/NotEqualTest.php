<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use PHPUnit\Framework\TestCase;
use Homeapp\Filter\DTO\Field\NotEqual;

class NotEqualTest extends TestCase
{
    /**
     * @dataProvider values
     */
    public function testIsEmpty($value, bool $isEmpty)
    {
        $greaterThan = new NotEqual('field', $value);

        self::assertEquals($isEmpty, $greaterThan->isEmpty());
    }

    /**
     * @dataProvider values
     */
    public function testGetValue($value)
    {
        $greaterThan = new NotEqual('field', $value);

        self::assertEquals($value, $greaterThan->getValue());
    }

    public function testGetName()
    {
        $fieldName = 'field';
        $greaterThan = new NotEqual('field', 'test');
        self::assertEquals($fieldName, $greaterThan->getName());
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
