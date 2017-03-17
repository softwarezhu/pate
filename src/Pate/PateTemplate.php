<?php
namespace Pate;
use Pate\Processors\AttributesProcessor;
use Pate\Processors\ConditionProcessor;
use Pate\Processors\ContentProcessor;
use Pate\Processors\DefineProcessor;
use Pate\Processors\RepeatProcessor;
use Pate\Processors\ReplaceProcessor;

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
        ReplaceProcessor::class,
    ];

    protected $compileDir = '/tmp';

    protected $cache = true;

    protected $debugMode = true;

    protected $compiledFileName;
    
    public function __construct()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $this->dom = $dom;
    }
    
    public function loadHtml($data)
    {
        $name = uniqid() . '.html';
        $this->compiledFileName = $this->compileDir . DIRECTORY_SEPARATOR . $name;
        @$this->dom->loadHTML($data);
    }
    public function loadHtmlFile($fileName)
    {
        $name = uniqid() . '.html';
        $this->compiledFileName = $this->compileDir . DIRECTORY_SEPARATOR . $name;
        @$this->dom->loadHTMLFile($fileName);
    }
    
    public function compile()
    {
        $this->parseElement($this->dom);

        $content = $this->dom->saveHTML();

        $replacedContent = preg_replace_callback('/\&lt\;\?php%20.+\?\&gt\;/', function($match){
            return urldecode(htmlspecialchars_decode($match[0]));
        }, $content);
        
        file_put_contents($this->compiledFileName, $replacedContent);
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

    public function render($_data = array(), $_return = true)
    {
        $this->compile();
        
        extract($_data);

        $_oldSetting = error_reporting(E_ALL ^ E_NOTICE);
        ob_start();
        include($this->compiledFileName);
        $content = ob_get_contents();
        ob_end_clean();
        error_reporting($_oldSetting);

        if ($_return) {
            return $content;
        } else {
            echo $content;
        }
    }
    
}