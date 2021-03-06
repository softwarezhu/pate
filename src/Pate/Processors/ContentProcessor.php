<?php
namespace Pate\Processors;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:52
 */
class ContentProcessor extends AbstractProcessor
{
    public $name = 'tal:content';

    public function process(\DOMElement $element, $expression)
    {
        $expression = $this->resolveExpression($expression);

        $this->text($element, 'echo ' . $expression . ';');
        
        $element->removeAttribute($this->name);
    }
}