<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\Point;
use Homeapp\FilteringRepository\Handlers\PointHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PointHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $handler = new PointHandler();
        self::assertTrue($handler->isFinal());
    }

    public function testIsSupportedWrongName(): void
    {
        $handler = new PointHandler();

        /** @var Point|MockObject */
        $field = $this->createStub(Point::class);
        self::assertFalse($handler->isSupported($field));
    }

    public function testIsSupportedTrue(): void
    {
        $handler = new PointHandler();

        /** @var Point|MockObject */
        $field = $this->createStub(Point::class);
        $field->expects(self::once())->method('getName')->willReturn('point');
        self::assertTrue($handler->isSupported($field));
    }

    public function testAddField(): void
    {
        $handler = new PointHandler();

        $lng = 12.2;

        $lat = 102.2;

        /** @var MockObject|Point */
        $field = $this->createPartialMock(
            Point::class,
            ['getLng', 'getLat', 'getName']
        );

        $field->expects(self::exactly(2))->method('getLng')->willReturn($lng);
        $field->expects(self::exactly(2))->method('getLat')->willReturn($lat);
        $field->expects(self::once())->method('getName')->willReturn('point');

        /** @var MockObject|QueryBuilder */
        $qb = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $qb->expects(self::exactly(4))
            ->method('andWhere')
            ->withConsecutive(
                ["a.%s.lng >= :leftP"],
                ["a.%s.lat >= :topP"],
                ["a.%s.lng <= :rightP"],
                ["a.%s.lat <= :bottomP"]
            )
            ->willReturnSelf();

        $qb->expects(self::exactly(4))
            ->method('setParameter')
            ->withConsecutive(
                ['leftP', $lng - Point::COORDINATES_CORRECTION],
                ['topP', $lat - Point::COORDINATES_CORRECTION],
                ['rightP', $lng + Point::COORDINATES_CORRECTION],
                ['bottomP', $lat + Point::COORDINATES_CORRECTION]
            )
            ->willReturnSelf();

        $handler->addFilter($field, $qb);
    }
}

