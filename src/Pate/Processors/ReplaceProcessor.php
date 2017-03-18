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
        $value = trim($expression);

        $exp = $this->resolveExpression($value);
        
        $this->replace($element, $exp);
    }
}