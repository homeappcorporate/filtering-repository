<?php

namespace Homeapp\Filter\DTO\Field;

class IsNull extends FilterField
{
    public function isEmpty(): bool
    {
        return false;
    }

    public function getValue()
    {
        return null;
    }
}
