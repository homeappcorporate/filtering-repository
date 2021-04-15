<?php

declare(strict_types=1);

namespace Test\Filter\DTO;

use DateTime;
use Homeapp\Filter\DTO\Field\Equal;
use Homeapp\Filter\DTO\Field\FromTo;
use Homeapp\Filter\DTO\Field\In;
use Homeapp\Filter\DTO\Field\IsNotNull;
use Homeapp\Filter\DTO\Field\IsNull;
use Homeapp\Filter\DTO\Field\Like;
use Homeapp\Filter\DTO\Field\Point;
use Homeapp\Filter\DTO\Field\Polygon;
use Homeapp\Filter\DTO\Field\Rectangle;
use Homeapp\Filter\DTO\Filter;
use Homeapp\Filter\DTO\FilterFactory;
use Homeapp\Filter\DTO\FilterFactoryQueryParseException;
use Homeapp\Filter\DTO\Sorting;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class FilterFactoryTest extends TestCase
{
    /**
     * @dataProvider values 
     */
    public function testFromArray(
        array $values,
        ?Filter $filter,
        bool $isEmpty,
        array $filters
    ): void {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray($values, $filter);
        self::assertEquals($isEmpty, $filter->isEmpty());
    }

    public function testAgeField(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(['filter' => ['age' => 12]], null);
        self::assertInstanceOf(FromTo::class, $filter->getField('updatedAt'));
    }

    public function testFromTo(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(['filter' => ['date' => ['from' => 12, 'to' => 12]]], null);
        self::assertInstanceOf(FromTo::class, $filter->getField('date'));
    }

    public function testPoint(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(['filter' => ['point' => ['lat' => 12, 'lng' => 12]]], null);
        self::assertInstanceOf(Point::class, $filter->getField('point'));
    }

    public function testPolygon(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [
                    'polygon' => [
                        'features' => [
                            ['geometry' => ['1', '2']]
                        ]
                    ]
                ]
            ],
            null
        );
        self::assertInstanceOf(Polygon::class, $filter->getField('polygon'));
    }

    public function testCoordinates(): void
    {
        $values = [
            Rectangle::TOP_LEFT => [Point::LNG => 0.1, Point::LAT => 0.1],
            Rectangle::BOTTOM_RIGHT => [Point::LAT => 0.1, Point::LNG => 0.2]
        ];
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [
                    'coordinates' => $values
                ]
            ],
            null
        );
        self::assertInstanceOf(Rectangle::class, $filter->getField('coordinates'));
    }

    public function testIn(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [
                    'some_in_value' => ['test', 'hell', 'heaven']
                ]
            ],
            null
        );
        self::assertInstanceOf(In::class, $filter->getField('some_in_value'));
    }

    public function testLike(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [
                    'like' => '%test'
                ]
            ],
            null
        );
        self::assertInstanceOf(Like::class, $filter->getField('like'));
    }

    public function testNotNull(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [
                    'not_null' => 'notnull'
                ]
            ],
            null
        );
        self::assertInstanceOf(IsNotNull::class, $filter->getField('not_null'));
    }

    public function testNull(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [
                    'null' => 'null'
                ]
            ],
            null
        );
        self::assertInstanceOf(IsNull::class, $filter->getField('null'));
    }

    public function testEqual(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [
                    'equal_field' => 12
                ]
            ],
            null
        );
        self::assertInstanceOf(Equal::class, $filter->getField('equal_field'));
    }

    public function testLimit(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [],
                'limits' => [
                    'count' => 12,
                    'page' => 1,
                ]
            ],
            null
        );
        self::assertEquals(1, $filter->getPage());
        self::assertEquals(12, $filter->getCount());
    }

    public function testView(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [],
                'viewType' => 'test'
            ],
            null
        );
        self::assertEquals('test', $filter->getViewType());
    }

    public function testErrorParseSortingField(): void
    {
        $this->expectException(FilterFactoryQueryParseException::class);
        $this->expectErrorMessage('Undefined `type` parameter in query');

        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [],
                'sorting' => [
                    'direction' => 'desc',
                ],
            ],
            null
        );
    }

    public function testParseSortingField(): void
    {
        $filterFactory = new FilterFactory();
        $filter = $filterFactory->fromArray(
            [
                'filter' => [],
                'sorting' => [
                    'direction' => 'desc',
                    'type' => 'test'
                ],
            ],
            null
        );

        self::assertEquals(new Sorting('test', 'desc'), $filter->getSorting());
    }

    public function values(): array
    {
        return [
            'nullable_filter' => [
                'values' => ['filter' => []],
                'filter' => null,
                'isEmpty' => true,
                'filters' => [],
            ],
            'field_not_string' => [
                'values' => ['filter' => [1 => 3]],
                'filter' => new Filter([]),
                'isEmpty' => true,
                'filters' => [],
            ]
        ];
    }
}
