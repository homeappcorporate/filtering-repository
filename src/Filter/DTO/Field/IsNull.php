<?php

namespace Homeapp\Filter\DTO\Field;

class IsNull extends FilterField
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
