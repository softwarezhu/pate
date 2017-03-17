# Pate
Pate is a PHP Attribute Template Engine inspired by PHPTAL but run much faster. It uses syntax like angular and vue, But renders on the server side. It's compatible with PHPTAL. 


## Why Use Pate Instead Of PHPTAL?
I use [PHPTAL](https://github.com/phptal/PHPTAL) as our template engine in one of my Project. But is was **TOO SLOW**!!, and **TOO COMPLICATED**. So I decide to rewrite a similar Template Engine named **PATE**.

Here are some advantages:
* TREE times faster than PHPTAL.  PHPTAL takes several seconds to render a big page(abount 150k), but PATE is more than 3 times faster. 
* PATE is Extremely Extensible. PATE is designed for extensible, You can create your own Syntax to enhance PATE's feature.
* PATE is Extremely Easy. There are only 1/10 codes of PHPTAL, but much more powerful.
* PATE's compiled file is VERY READABLE. But PHPTAL's compiled file is complicated and very hard to read.

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

## HOW TO USE
