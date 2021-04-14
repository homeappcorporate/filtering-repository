<?php

declare(strict_types=1);

namespace Test\Filter\DTO;

use Homeapp\Filter\DTO\FilterFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class FilterFactoryFromRequestTest extends TestCase
{
    /**
     * @dataProvider values 
     */
    public function testFromRequest(
        Request $values,
        bool $isEmpty
    ): void {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromRequest($values);
        self::assertEquals($isEmpty, $filter->isEmpty());
    }

    public function values(): array
    {
        return [
            'nullable_filter' => [
                'values' => new Request(['filter' => [], 'sorting' => [], 'viewType' => []]),
                'isEmpty' => true,
                'filters' => [],
            ],
            'field_not_string' => [
                'values' => new Request(['filter' => [1 => 3], 'sorting' => [], 'viewType' => []]),
                'isEmpty' => true,
                'filters' => [],
            ]
        ];
    }
}
