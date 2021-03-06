<?php
namespace Pate\Processors;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:52
 */
class DefineProcessor extends AbstractProcessor
{
    public $name = 'tal:define';

    public function process(\DOMElement $element, $expression)
    {
        list($name, $var) = $this->splitExpression($expression);
        $name = $this->resolveExpression($name);
        $var = $this->resolveExpression($var);
        $startCode = $name . ' = ' . $var . '; ';
        
        $this->before($element, $startCode);
       
        $element->removeAttribute($this->name);
    }
}