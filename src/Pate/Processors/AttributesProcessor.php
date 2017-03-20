<?php
namespace Pate\Processors;
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:52
 */
class AttributesProcessor extends AbstractProcessor
{
    public $name = 'tal:attributes';
    
    public function process(\DOMElement $element, $expression)
    {
        $content = $expression;
        
        $expressions = preg_split('/;/', $content);
        foreach ($expressions as $i => $expression) {
            $expression = trim($expression);
            if (empty($expression)) {
                continue;
            }
            list($attr, $val) = $this->splitExpression($expression);

            $exp = $this->resolveExpression($val);
            $element->setAttribute($attr, '<?php echo ' . $exp . '; ?>');
        }

        $element->removeAttribute($this->name);
    }
}