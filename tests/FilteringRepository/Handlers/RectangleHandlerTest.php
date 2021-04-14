<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use PHPUnit\Framework\TestCase;
use Homeapp\FilteringRepository\Handlers\RectangleHandler;
use Homeapp\Filter\DTO\Field\Rectangle;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\MockObject\MockObject;

class RectangleHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $handler = new RectangleHandler();
        self::assertFalse($handler->isFinal());
    }

    public function testIsSupportedWrongName(): void
    {
        $handler = new RectangleHandler();

        /** @var Rectangle|MockObject */
        $field = $this->createStub(Rectangle::class);
        self::assertFalse($handler->isSupported($field));
    }

    public function testIsSupportedTrue(): void
    {
        $handler = new RectangleHandler();

        /** @var Rectangle|MockObject */
        $field = $this->createStub(Rectangle::class);
        $field->expects(self::once())->method('getName')
            ->willReturn('fieldNamecoordinates');
        self::assertTrue($handler->isSupported($field));
    }

    public function testAddField(): void
    {
        $handler = new RectangleHandler();

        $left = 12.2;

        $right = 102.2;

        $bottom = 102.2;

        $top = 102.2;

        /** @var MockObject|Rectangle */
        $field = $this->createPartialMock(
            Rectangle::class,
            ['getLeft', 'getTop', 'getRight', 'getBottom', 'getName']
        );

        $field->expects(self::once())
            ->method('getName')
            ->willReturn('coordinates');

        $field->expects(self::once())->method('getLeft')->willReturn($left);
        $field->expects(self::once())->method('getTop')->willReturn($right);
        $field->expects(self::once())->method('getRight')->willReturn($bottom);
        $field->expects(self::once())->method('getBottom')->willReturn($top);

        /** @var MockObject|QueryBuilder */
        $qb = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $qb->expects(self::exactly(4))
            ->method('andWhere')
            ->withConsecutive(
                ["a.lng >= :left"],
                ["a.lat >= :top"],
                ["a.lng <= :right"],
                ["a.lat <= :bottom"]
            )
            ->willReturnSelf();

        $qb->expects(self::exactly(4))
            ->method('setParameter')
            ->withConsecutive(
                ['left', $left - Rectangle::COORDINATES_CORRECTION],
                ['top', $top - Rectangle::COORDINATES_CORRECTION],
                ['right', $right + Rectangle::COORDINATES_CORRECTION],
                ['bottom', $bottom + Rectangle::COORDINATES_CORRECTION]
            )
            ->willReturnSelf();

        $handler->addFilter($field, $qb);
    }
}

