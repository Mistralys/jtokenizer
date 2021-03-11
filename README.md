# JTokenizer

Modernized, composer compatible version of Tim Whitlock's JTokenizer PHP Javascript parser and tokenizer library.

## Overview

The JavaScript tokenizer is designed to mimic the PHP tokenizer. Possible uses for the 
tokenizer include code highlighting and simple manipulation of JavaScript source code.

## Quickstart

Tokenize a javascript file:

```php
$source = file_get_contents('javascript.js');
$tokens = \JTokenizer\JTokenizer::getTokens($source);
```

`getTokens()` behaves the same as the PHP `token_get_all` function, with the addition of 
a column number as well as a line number. Additionally there is the static `getTokenName()`
method that acts like the PHP `token_name` function.

## Source

Derived from Tim Whitlock's JTokenizer package, released back in 2009. As far as 
my research online showed, no one else published a modernized version of it that
can run without deprecated messages on PHP7.4+.

The original sources are here:

https://timwhitlock.info/blog/2009/11/jparser-and-jtokenizer-released/

