# JTokenizer

Modernized, composer compatible version of Tim Whitlock's JTokenizer PHP Javascript parser and tokenizer library.

  > NOTE: This package is a good choice for very basic access to JavaScript tokens.
    A more modern and complete solution is Peast: https://github.com/mck89/peast

## Overview

The JavaScript tokenizer is designed to mimic the PHP tokenizer. Possible uses for the 
tokenizer include code highlighting and simple manipulation of JavaScript source code.

## Quickstart

Tokenize a JavaScript file:

```php
$tokens = \JTokenizer\JTokenizer::getFileTokens('javascript.js');
```

Tokenize a JavaScript string:

```php
$jsCode = '(JavaScript source code)';
$tokens = \JTokenizer\JTokenizer::getTokens($jsCode);
```

Both methods behave like the PHP `token_get_all` function, with the addition of 
a column number as well as a line number. 

## Token information

The `getTokens()` method returns an indexed array with a list of token arrays. Each token 
array is an indexed array with the following information:

1) Token identifier
2) Matched literal
3) Line number
4) Column number

The token identifier can be either an Integer, or a string. To get the according token
type name, use the `getTokenName()` method.

## Source

Derived from Tim Whitlock's JTokenizer package, released back in 2009. As far as 
my research online showed, no one else published a modernized version of it that
can run without deprecated messages on PHP7.4+.

The original sources are here:

https://timwhitlock.info/blog/2009/11/jparser-and-jtokenizer-released/

