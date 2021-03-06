<?php
namespace Pate\Processors;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:52
 */
class RepeatProcessor extends AbstractProcessor
{
    public $name = 'tal:repeat';

    public function process(\DOMElement $element, $expression)
    {
        list($name, $lists) = $this->splitExpression($expression);
        $name = $this->resolveExpression($name);
        $lists = $this->resolveExpression($lists);

        $startCode = 'foreach (' . $lists . ' as $_index => ' . $name . '): ';
        $endCode = 'endforeach; ';

        $this->before($element, $startCode);
        $this->after($element, $endCode);

        $element->removeAttribute($this->name);
    }
}