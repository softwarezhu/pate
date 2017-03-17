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
        $value = trim($expression);
        list($item, $items) = preg_split('/\s+/', $value);
        
        $exp = $this->resolveExpression($items);
        
        $startCode = 'foreach (' . $exp . ' as $_index => $' . $item . '): ';
        $endCode = 'endforeach; ';

        $this->before($element, $startCode);
        $this->after($element, $endCode);

        $element->removeAttribute($this->name);
    }
}