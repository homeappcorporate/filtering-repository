<?php

namespace Homeapp\Filter\DTO\Field;

abstract class FilterField implements FieldInterface
{
    /**
     * @deprecated Это какой-то позор
     */
    const CONST_DEFAULT_ALIAS = 'a';

    /**
     * @var string
     */
    protected $name;

    /**
     * @deprecated Это какой-то позор
     */
    protected $alias = 'a';

    public function __construct(string $name, $alias = null)
    {
        $this->name = $name;
        $this->alias = $alias;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function withName(string $name): self
    {
        $o = clone $this;
        $o->name = $name;

        return $o;
    }

    abstract public function isEmpty(): bool;

    /**
     * @deprecated Этого не должно быть тут
     */
    public function getAlias(): string
    {
        return $this->alias ?: self::CONST_DEFAULT_ALIAS;
    }

    public function withAlias(?string $alias): self
    {
        $o = clone $this;
        $o->alias = $alias;

        return $o;
    }
}
