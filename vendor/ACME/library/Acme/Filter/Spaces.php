<?php
namespace Acme\Filter;

use Zend\Filter\FilterInterface;

class Spaces implements FilterInterface
{

    public function filter($value)
    {
    	return str_replace(' ','_', $value);
    }
}