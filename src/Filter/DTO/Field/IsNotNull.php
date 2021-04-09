<?php

declare(strict_types=1);

namespace Homeapp\Filter\DTO\Field;

class IsNotNull extends FilterField
{
    public function isEmpty(): bool
    {
        return false;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return null;
    }
}
