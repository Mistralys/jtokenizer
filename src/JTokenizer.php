<?php

namespace JTokenizer;

class JTokenizer extends JTokenizerBase
{
    protected $regPunc = '/(?:\>\>\>\=|\>\>\>|\<\<\=|\>\>\=|\!\=\=|\=\=\=|&&|\<\<|\>\>|\|\||\*\=|\|\=|\^\=|&\=|%\=|-\=|\+\+|\+\=|--|\=\=|\>\=|\!\=|\<\=|;|,|\<|\>|\.|\]|\}|\(|\)|\[|\=|\:|\||&|-|\{|\^|\!|\?|\*|%|~|\+)/';

    function __construct(bool $whitespace, bool $unicode)
    {
        parent::__construct($whitespace, $unicode);
        $this->Lex = Lex::get('JLex');
    }

    public static function getTokens(string $src, bool $whitespace = true, bool $unicode = true)
    {
        $Tokenizer = new JTokenizer($whitespace, $unicode);

        return $Tokenizer->get_all_tokens($src);
    }

    public static function getTokenName($t)
    {
        $Lex = Lex::get('JLex');

        return $Lex->name($t);
    }
}
