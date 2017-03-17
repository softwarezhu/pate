<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/3/30
 * Time: 16:36
 */
use Pate\PateTemplate;

require __DIR__ . '/../vendor/autoload.php';

$template = new PateTemplate();
$template->loadHtmlFile('data/bigHtml.html');
$xml = $template->compile();
$content = $xml->saveHTML();
$replacedContent = preg_replace_callback('/\&lt\;\?php%20.+\?\&gt\;/', function($match){
    return urldecode(htmlspecialchars_decode($match[0]));
}, $content);

file_put_contents('result2.php', $replacedContent);
