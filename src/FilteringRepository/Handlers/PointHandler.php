<?php declare(strict_types=1);

namespace Homeapp\FilteringRepository\Handlers;

use Doctrine\ORM\QueryBuilder;
use Homeapp\Filter\DTO\Field\FilterField;
use Homeapp\Filter\DTO\Field\Point;

class PointHandler implements FilteringHandlerInterface
{
    public function isFinal(): bool
    {
        return true;
    }

    public function isSupported(FilterField $field): bool
    {
        return $field instanceof Point && $field->getName() === 'point';
    }

    public function addFilter(FilterField $field, QueryBuilder $qb): void
    {
        if ($field instanceof Point && $field->getName() === 'point') {
            $qb->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS.'.%s.lng >= :leftP')
               ->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS.'.%s.lat >= :topP')
               ->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS.'.%s.lng <= :rightP')
               ->andWhere(FilteringHandlerInterface::DEFAULT_ALIAS.'.%s.lat <= :bottomP');
            $qb->setParameter('leftP', $field->getLng() - Point::COORDINATES_CORRECTION)
               ->setParameter('topP', $field->getLat() - Point::COORDINATES_CORRECTION)
               ->setParameter('rightP', $field->getLng() + Point::COORDINATES_CORRECTION)
               ->setParameter('bottomP', $field->getLat() + Point::COORDINATES_CORRECTION);
        }
    }
}
