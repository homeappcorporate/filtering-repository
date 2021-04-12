<?php

declare(strict_types=1);

namespace Test\Filter\DTO\Field;

use PHPUnit\Framework\TestCase;
use Homeapp\Filter\DTO\Field\Like;

class LikeTest extends TestCase
{
    /**
     * @dataProvider values
     */
    public function testIsEmpty($value, bool $isEmpty)
    {
        $greaterThan = new Like('field', $value);

        self::assertEquals($isEmpty, $greaterThan->isEmpty());
    }

    /**
     * @dataProvider values
     */
    public function testGetValue($value)
    {
        $greaterThan = new Like('field', $value);

        self::assertEquals($value, $greaterThan->getValue());
    }

    public function testGetName()
    {
        $fieldName = 'field';
        $greaterThan = new Like('field', 'test');
        self::assertEquals($fieldName, $greaterThan->getName());
    }

    public function values(): array
    {
        return [
            ['value' => '1', 'empty' => false, 'true' => false, 'false' => false],
            ['value' => 'true', 'empty' => false, 'true' => true, 'false' => false],
            ['value' => 'false', 'empty' => false, 'true' => false, 'false' => true],
            ['value' => '', 'empty' => true, 'true' => false, 'false' => false],
        ];
    }
}
