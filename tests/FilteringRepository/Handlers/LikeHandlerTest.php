<?php

declare(strict_types=1);

namespace Test\Filter\ngRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\Like;
use Homeapp\FilteringRepository\Handlers\LikeHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LikeHandlerTest extends TestCase
{
    public function testIsFinal(): void
    {
        $like = new LikeHandler();
        self::assertTrue($like->isFinal());
    }

    public function testIsSupportedTrue(): void
    {
        $likeHandler = new LikeHandler();

        /** @var MockObject|Like */
        $stub = $this->createStub(Like::class);
        self::assertTrue($likeHandler->isSupported($stub));
    }

    public function testIsSupportedFalse(): void
    {
        $likeHandler = new LikeHandler();

        /** @var FilterField|MockObject */
        $stub = $this->getMockForAbstractClass(FilterField::class, ['test']);
        self::assertFalse($likeHandler->isSupported($stub));
    }

    public function testAddFiled(): void
    {
        $like = new LikeHandler();

        $fieldName = 'fieldName';
        $fieldValue = 'hello';

        /** @var Like|MockObject */
        $likeMock = $this->createPartialMock(
            Like::class,
            ['getValue', 'getName']
        );

        $likeMock->expects(self::once())
            ->method('getValue')
            ->willReturn($fieldValue);

        $likeMock->expects(self::exactly(3))
            ->method('getName')
            ->willReturn($fieldName);

        /** @var QueryBuilder|MockObject */
        $qbMock = $this->createPartialMock(
            QueryBuilder::class,
            ['andWhere', 'setParameter']
        );

        $qbMock->expects(self::once())
            ->method('andWhere')
            ->with("a.{$fieldName} LIKE :a{$fieldName}Eq")
            ->willReturnSelf();

        $qbMock->expects(self::once())
            ->method('setParameter')
            ->with("a{$fieldName}Eq", "%{$fieldValue}%")
            ->willReturnSelf();

        $like->addFilter($likeMock, $qbMock);
    }
}

