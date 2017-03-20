# Pate
Pate is a PHP Attribute Template Engine inspired by PHPTAL but run much faster. It uses syntax like angular and vue, But renders on the server side. It's compatible with PHPTAL. 


## Why Use Pate Instead Of PHPTAL?
I use [PHPTAL](https://github.com/phptal/PHPTAL) as our template engine in one of my Project. But is was **TOO SLOW**!!, and **TOO COMPLICATED**. So I decide to rewrite a similar Template Engine named **PATE**.

Here are some advantages:
* TREE times faster than PHPTAL.  PHPTAL takes several seconds to render a big page(abount 150k), but PATE is more than 3 times faster. 
* PATE is Extremely Extensible. PATE is designed for extensible, You can create your own Syntax to enhance PATE's feature.
* PATE is Extremely Easy. There are only 1/10 codes of PHPTAL, but much more powerful.
* PATE's compiled file is VERY READABLE. But PHPTAL's compiled file is complicated and very hard to read.
* PHPTAL is too strict with dom and variables, error occurs frequently. Pate is friendly.

Requirements
---
To use Pate, you need

* PHP >= 5.4.

* With **dom** extension installed.


## Install

With composer

> composer require softwarezhu/pate


Examples
==============

## Attributes

Template file

```html
<html>
<head>
</head>
<body>
<img tal:attributes="src data/src; alt data/title"/>
</body>
</html>
```

Do render(ie. This template is named 'template.html')

```php
use Pate\PateTemplate;

$template = new PateTemplate();
$template->loadHtmlFile('template.html');
$content = $template->render(array(
    'data' => array(
        'src' => 'htttp://github.com/logo.jpg',
        'title' => 'Github Logo'
    )
));

echo $content;
```

The render result will be

```html
<html>
    <head>
    </head>
    <body>
        <img src="htttp://github.com/logo.jpg" alt="Github Logo"/>
    </body>
</html>
```


Template Syntax
========
## Attributes
```html
 <img tal:attributes="src data/src; alt data/title"/>
```

The img *src* and *alt* attributes will be replaced by the data['src'] value and data['title'] value. 
The multiple attributes use ";" to separate.

## Text
```html
 <span tal:content="data/title">This content will be replaced after rendered. </span>
```

## Loop
```html
<tr tal:repeat="product products" tal:content="product/title">This content will be replaced by product/title after rendered. </tr>
```

If the count of products is N, there will be N tr lines. 

## If
```html
 <div tal:condition="product/isPromote" tal:content="product/discount">If product/isPromote is false, this block will be removed from the dom. </div>
```

`tal:condition` likes `if` in php. 

## Replace
```html
 <div tal:replace="123">This entire DIV will be replaced with '123'(not the inner text). </div>
 ```