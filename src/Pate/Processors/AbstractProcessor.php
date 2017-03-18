<?php
namespace Pate\Processors;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:52
 */
abstract class AbstractProcessor
{
    public $name;
    
    public function __construct()
    {
    }
    
    abstract public function process(\DOMElement $element, $expression);

    public function resolveExpression($expression)
    {
        $expression = trim($expression);
        // 将phptal的表达式转化为php的代码

        // php格式
        if (strpos($expression, 'php:') === 0) {
            $exp = preg_replace('/php:/', '', $expression);
            // 里面的variable格式需要替换。不带$符号，且有数组形式
            if (strpos($exp, '$') === false && preg_match('/(\w+)\[/', $exp, $matches) === 1) {
                $exp = preg_replace('/(\w+)\[/', '$$1[', $exp);
            }
            return $exp;
        }
        // phptal格式
        if (strpos($expression, '$') === false) {
            $arr = explode('/', $expression);
            $str = '$';
            for ($i = 0; $i < count($arr); $i++) {
                if ($i ==1) {
                    $str .= "['";
                } else if ($i > 1 && $i < count($arr)) {
                    $str .= "']['";
                }

                $str .= $arr[$i];

                if ($i >= 1 && $i == count($arr)-1) {
                    $str .= "']";
                }
            }

            return $str;
        }

        return $expression;
    }

    /**
     * insert a php code before an element
     * @param \DOMElement $element
     * @param $phpExpression
     */
    public function before(\DOMElement $element, $phpExpression)
    {
        $exp = new \DOMProcessingInstruction('php', $phpExpression . ' ?');
        $newLine = new \DOMText("\r\n");
        $element->parentNode->insertBefore($exp, $element);
        $element->parentNode->insertBefore($newLine, $element);
    }

    /**
     * insert a php code after an element
     * @param \DOMElement $element
     * @param $phpExpression
     */
    public function after(\DOMElement $element, $phpExpression)
    {
        $exp = new \DOMProcessingInstruction('php', $phpExpression . ' ?');
        $newLine = new \DOMText("\r\n");

        if ($element->nextSibling) {
            $element->parentNode->insertBefore($newLine, $element->nextSibling);
            $element->parentNode->insertBefore($exp, $element->nextSibling);
        } else {
            $element->parentNode->appendChild($newLine);
            $element->parentNode->appendChild($exp);
        }
    }
    
    public function text(\DOMElement $element, $phpExpression)
    {
        $exp = new \DOMProcessingInstruction('php', $phpExpression . ' ?');

        while($element->childNodes->length){
            $element->removeChild($element->firstChild);
        }
        $element->appendChild($exp);
    }
    
    public function replace(\DOMElement $element, $phpExpression = null)
    {
        $phpExpression = trim($phpExpression);
        if (empty($phpExpression)) {
            $element->parentNode->removeChild($element);
        } else {
            $this->before($element, $phpExpression);
            $element->parentNode->removeChild($element);
        }
    }
}