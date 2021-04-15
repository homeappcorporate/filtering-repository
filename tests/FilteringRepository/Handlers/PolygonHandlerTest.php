<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\Polygon;
use Homeapp\FilteringRepository\Handlers\PolygonHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function json_encode;

class PolygonHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $handler = new PolygonHandler();
        self::assertTrue($handler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $handler = new PolygonHandler();

        /** @var MockObject|Polygon */
        $field = $this->createStub(Polygon::class);
        self::assertTrue($handler->isSupported($field));
    }

    public function testIsSupportedFalse(): void
    {
        $handler = new PolygonHandler();

        /** @var MockObject|FilterField */
        $field = $this->getMockForAbstractClass(FilterField::class, ['test']);

        self::assertFalse($handler->isSupported($field));
    }

    public function testAddField(): void
    {
        $handler = new PolygonHandler();

        $fieldName = 'fieldName';
        $fieldValue = [];

        /** @var Polygon|MockObject */
        $field = $this->createPartialMock(
            Polygon::class,
            ['getName', 'getGeoJson']
        );

        $field->expects(self::exactly(2))
            ->method('getName')
            ->willReturn($fieldName);

        $field->expects(self::once())
            ->method('getGeoJson')
            ->willReturn($fieldValue);

        /** @var QueryBuilder|MockObject */
        $qb = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $qb->expects(self::once())
            ->method('andWhere')
            ->with("ST_Contains( ST_GeomFromGeoJSON(:{$fieldName}Poly), ST_Point(a.lng, a.lat)) = 't'")
            ->willReturnSelf();

        $qb->expects(self::once())
            ->method('setParameter')
            ->with("{$fieldName}Poly", json_encode($fieldValue));

        $handler->addFilter($field, $qb);
    }
}

