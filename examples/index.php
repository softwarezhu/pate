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
$content = $template->render([
    'block1' => 'aaa',
    'block9' => array(
        'products' => null
    ),
]);

file_put_contents('result2.php', $content);
