<?php
namespace Pate\Processors;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:52
 */
class ConditionProcessor extends AbstractProcessor
{
    public $name = 'tal:condition';

    public function process(\DOMElement $element, $expression)
    {
        $exp = $this->resolveExpression($expression);

        $startCode = 'if (' . $exp . '): ';
        $endCode = 'endif; ';
        
        $this->before($element, $startCode);
        $this->after($element, $endCode);
        
        $element->removeAttribute($this->name);
    }
}