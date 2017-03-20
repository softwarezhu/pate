<?php
namespace Pate\Processors;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:52
 */
class ReplaceProcessor extends AbstractProcessor
{
    public $name = 'tal:replace';

    public function process(\DOMElement $element, $expression)
    {
        $expression = $this->resolveExpression($expression);

        $this->replace($element, $expression);
        
        return true;
    }
}