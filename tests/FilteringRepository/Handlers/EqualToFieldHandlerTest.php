<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\EqualToField;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\FilteringRepository\Handlers\EqualToFieldHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EqualToFieldHandlerTest extends TestCase
{
    public function testIsFinalTrue(): void
    {
        $equalHandler = new EqualToFieldHandler();
        self::assertTrue($equalHandler->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $equalHandler = new EqualToFieldHandler();

        /** @var MockObject|EqualToField */
        $stub = $this->createStub(EqualToField::class);
        self::assertTrue($equalHandler->isSupported($stub));
    }

    public function testIsSupportedFalse(): void
    {
        $equalHandler = new EqualToFieldHandler();

        /** @var FilterField|MockObject */
        $stub = $this->getMockForAbstractClass(FilterField::class, ['test']);
        self::assertFalse($equalHandler->isSupported($stub));
    }

    public function testAddFiled(): void
    {
        $equalHandler = new EqualToFieldHandler();

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere']
        );

        $fieldName = 'test';
        $fieldValue = 'hell';

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} = {$fieldValue}")
            ->willReturnSelf();

        /** @var EqualToField|MockObject */
        $equalMock = $this->createPartialMock(
            EqualToField::class,
            ['getName', 'getValue']
        );

        $equalMock->expects(self::once())
            ->method('getValue')
            ->willReturn($fieldValue);

        $equalMock
            ->expects(self::once())
            ->method('getName')
            ->willReturn($fieldName);

        $equalHandler->addFilter($equalMock, $qbMock);
    }
}

