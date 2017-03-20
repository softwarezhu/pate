<?php
namespace Pate;
use Pate\Processors\AttributesProcessor;
use Pate\Processors\ConditionProcessor;
use Pate\Processors\ContentProcessor;
use Pate\Processors\DefineProcessor;
use Pate\Processors\RepeatProcessor;
use Pate\Processors\ReplaceProcessor;
use Pate\Resolvers\DefaultResolver;
use Pate\Resolvers\SyntaxResolver;

/**
 * User: softwarezhu
 * Date: 2017/3/17
 * Time: 15:40
 */
class PateTemplate
{
    protected $dom;
    
    public $defaultProcessors = array(
        DefineProcessor::class,
        ConditionProcessor::class,
        RepeatProcessor::class,
        AttributesProcessor::class,
        ContentProcessor::class,
        ReplaceProcessor::class,
    );

    protected $processors = null;

    protected $resolver = null;
    
    protected $deleteElement = null;

    protected $compileDir;

    protected $cache = true;

    protected $debugMode = true;

    public $compiledFileName;

    public $compiled = false;
    
    public function __construct()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $this->dom = $dom;
        $this->compileDir = sys_get_temp_dir();

        $this->initDefaults();
    }
    
    protected function initDefaults()
    {
        $resolver = new DefaultResolver();
        $this->setResolver($resolver);

        $processors = array();
        foreach ($this->defaultProcessors as $processorClass) {
            $processors[] = new $processorClass($resolver);
        }

        $this->setProcessors($processors);
    }

    /**
     * Set Processors.
     *
     * @param array $processors
     */
    public function setProcessors($processors = array())
    {
        $this->processors = $processors;
    }

    /**
     * Set resolver.
     *
     * @param SyntaxResolver $resolver
     */
    public function setResolver(SyntaxResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Load html data.
     * @param $data
     */
    public function loadHtml($data)
    {
        $name = uniqid() . '.htmlc';
        $this->compiledFileName = $this->compileDir . DIRECTORY_SEPARATOR . $name;
        @$this->dom->loadHTML($data);
    }

    /**
     * Load html data from file.
     *
     * @param $fileName
     */
    public function loadHtmlFile($fileName)
    {
        $name = uniqid() . '.htmlc';
        $this->compiledFileName = $this->compileDir . DIRECTORY_SEPARATOR . $name;
        @$this->dom->loadHTMLFile($fileName);
    }

    /**
     * Compile the html, and return the compiled file.
     *
     * @return string the compiled file.
     */
    public function compile()
    {
        $this->parseElement($this->dom);

        $content = $this->dom->saveHTML();

        $replacedContent = preg_replace_callback('/\&lt\;\?php.+\?\&gt\;/', function($match){
            return urldecode(htmlspecialchars_decode($match[0]));
        }, $content);

        file_put_contents($this->compiledFileName, $replacedContent);

        $this->compiled = true;

        return $this->compiledFileName;
    }

    
    /**
     * @param \DOMNode $element
     */
    public function parseElement(\DOMNode $element)
    {
        // delete 
        if ($this->deleteElement) {
            $this->deleteElement->parentNode->removeChild($this->deleteElement);
            $this->deleteElement = null;
        }
        
        if ($element->hasAttributes()) {
            foreach ($this->processors as $processor) {
                if (!$element->hasAttribute($processor->name)) {
                    continue;
                }
                $isDelete = $processor->process($element, $element->getAttribute($processor->name));
                if ($isDelete) {
                    $this->deleteElement = $element;
                    return;
                }
            }
        }
        
        if ($element->hasChildNodes()) {
            foreach ($element->childNodes as $childNode) {
                $this->parseElement($childNode);
            }
        }
    }

    /**
     * Render the compiled php code by data.
     * @param array $_data
     * @param bool $_return
     * @return mixed
     */
    public function render($_data = array(), $_return = true)
    {
        if (!$this->compiled ) {
            $this->compile();
        }

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