<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\In;
use Homeapp\FilteringRepository\Handlers\InHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $inHandler = new InHandler();

        self::assertTrue($inHandler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $equalHandler = new InHandler();

        /** @var MockObject|In */
        $stub = $this->createStub(In::class);
        self::assertTrue($equalHandler->isSupported($stub));
    }

    public function testIsSupportedFalse(): void
    {
        $equalHandler = new InHandler();

        /** @var FilterField|MockObject */
        $stub = $this->getMockForAbstractClass(FilterField::class, ['test']);
        self::assertFalse($equalHandler->isSupported($stub));
    }

    public function testAddField(): void
    {
        $equalHandler = new InHandler();

        $fieldName = 'fieldName';
        $fieldValue = ['value'];

        /** @var In|MockObject */
        $inMock = $this->createPartialMock(In::class, ['getName', 'getValues']);
        $inMock->expects(self::exactly(3))
            ->method('getName')
            ->willReturn($fieldName);

        $inMock->expects(self::once())
            ->method('getValues')
            ->willReturn($fieldValue);

        /** @var MockObject|QueryBuilder */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} IN (:a{$fieldName}In)")
            ->willReturnSelf();

        $qbMock->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}In", $fieldValue)
            ->willReturnSelf();

        $equalHandler->addFilter($inMock, $qbMock);
    }
}

