<?php
namespace Pate;
use Pate\Processors\AttributesProcessor;
use Pate\Processors\ConditionProcessor;
use Pate\Processors\ContentProcessor;
use Pate\Processors\DefineProcessor;
use Pate\Processors\RepeatProcessor;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/3/17
 * Time: 15:40
 */
class PateTemplate
{
    protected $dom;
    
    public $processors = [
        DefineProcessor::class,
        ConditionProcessor::class,
        RepeatProcessor::class,
        AttributesProcessor::class,
        ContentProcessor::class,
    ];
    
    public function __construct()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $this->dom = $dom;
    }
    
    public function loadHtml($data)
    {
        @$this->dom->loadHTML($data);
    }
    public function loadHtmlFile($fileName)
    {
        @$this->dom->loadHTMLFile($fileName);
    }
    
    public function compile()
    {
        $this->parseElement($this->dom);
        
        return $this->dom;
    }

    /**
     * @param DOMNode $element
     */
    public function parseElement(\DOMNode $element)
    {
        // 查找变量，并增加scope
        if ($element->hasChildNodes()) {
            for ($i = 0; $i < $element->childNodes->length; $i++) {
                $childNode = $element->childNodes->item($i);

                $this->parseElement($childNode);
            }
        }

        if ($element->hasAttributes()) {
            for ($i = 0; $i < $element->attributes->length; $i++) {
                foreach ($this->processors as $processorName) {
                    /**
                     * @var TemplateProcessor
                     */
                    $processor = new $processorName();
                    if (!$element->hasAttribute($processor->name)) {
                        continue;
                    }
                    
                    $processor->process($element, $element->getAttribute($processor->name));
                }
            }

        }

    }

    public function render($data)
    {
        
    }
}