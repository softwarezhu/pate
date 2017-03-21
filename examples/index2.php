<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2016/3/30
 * Time: 16:36
 */
use Pate\PateTemplate;
use Pate\Resolvers\TALResolver;

require __DIR__ . '/../vendor/autoload.php';

$time = microtime(true);

$template = new PateTemplate();
$resolver = new TALResolver();
$template->setResolver($resolver);

$template->loadHtmlFile('data/big2.html');

$template->compile();

file_put_contents('result2.php', file_get_contents($template->compiledFileName));
echo '----time is : ' . (microtime(true) - $time) . PHP_EOL;

