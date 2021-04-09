<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\Rectangle;
use Doctrine\ORM\QueryBuilder;

class RectangleHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return false;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof Rectangle && false !== strpos($field->getName(), 'coordinates');
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof Rectangle && $field->getName() === 'coordinates') {
            $qb->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS . '.lng >= :left')
               ->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS . '.lat >= :top')
               ->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS . '.lng <= :right')
               ->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS . '.lat <= :bottom');
            $qb->setParameter('left', $field->getLeft() - Rectangle::COORDINATES_CORRECTION)
               ->setParameter('top', $field->getTop() - Rectangle::COORDINATES_CORRECTION)
               ->setParameter('right', $field->getRight() + Rectangle::COORDINATES_CORRECTION)
               ->setParameter('bottom', $field->getBottom() + Rectangle::COORDINATES_CORRECTION);
        }
    }
}
