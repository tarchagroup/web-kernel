<?php

namespace Tarcha\WebKernel\Filters;

use Aura\Filter\FilterContainer;

class AbstractFilter
{
    public function __construct(FilterContainer $factory)
    {
        $this->factory = $factory;
        $this->filter = $factory->newFilter();
    }

    public function addId($field)
    {
        $this->filter
            ->validate($field)
            ->is('int')
            ->asSoftRule();

        $this->filter
            ->validate($field)
            ->isNot('max', 0)
            ->asSoftRule();
    }

    public function addBlank($field)
    {
        $this->filter
            ->validate($field)
            ->is('blank')
            ->asSoftRule();
    }

    public function addString($field)
    {
        $this->filter
            ->validate($field)
            ->is('string')
            ->asSoftRule();
    }

    public function addInt($field)
    {
        $this->filter
            ->validate($field)
            ->is('int')
            ->asSoftRule();
    }

    public function addFloat($field)
    {
        $this->filter
            ->validate($field)
            ->is('float')
            ->asSoftRule();
    }

    public function addUuid($field)
    {
        $this->filter
            ->validate($field)
            ->is('uuidHexonly')
            ->asSoftRule();
    }

    public function addTime($field)
    {
        $this->filter
            ->validate($field)
            ->is('int')
            ->asSoftRule();

        $this->filter
            ->validate($field)
            ->isNot('max', 0)
            ->asSoftRule();
    }

    public function validate($values)
    {
        return $this->filter->apply($values);
    }

    public function newFilter()
    {
        $this->filter = $this->factory->newFilter();
        return $this->filter;
    }

    public function messages()
    {
        return $this->filter->getMessages();
    }
}
