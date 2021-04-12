<?php

declare(strict_types=1);

namespace Test\Filter\DTO;

use Homeapp\Filter\DTO\Sorting;
use PHPUnit\Framework\TestCase;

class SortingTest extends TestCase
{
    public function testGetType()
    {
        $type = '1';
        $sortingType = new Sorting($type, 'asc');
        self::assertEquals($type, $sortingType->getType());
    }

    public function testSetType()
    {
        $type = '1';
        $newType = '2';
        $sortingType = new Sorting($type, 'asc');
        $sortingType->setType($newType);
        self::assertEquals($newType, $sortingType->getType());
    }

    public function testGetDirection()
    {
        $direction = 'asc';
        $sortingType = new Sorting('1', $direction);
        self::assertEquals($direction, $sortingType->getDirection());
    }

    public function testSetDirection()
    {
        $direction = 'asc';
        $sortingType = new Sorting('1', $direction);
        $desc = 'desc';
        $sortingType->setDirection($desc);
        self::assertEquals($desc, $sortingType->getDirection());
    }
}

