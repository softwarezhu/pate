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
        $value = trim($expression);
        $pos = strpos($value, ' ');
        $var1 = substr($value, 0, $pos);
        $var2 = substr($value, $pos);

        $exp = $this->resolveExpression($var2);
        
        $startCode = '$' . $var1 . ' = ' . $exp . '; ';
        
        $this->before($element, $startCode);
       
        $element->removeAttribute($this->name);
    }
}