<?php

namespace JTokenizer;

class JTokenizer extends JTokenizerBase
{
    /**
     * @var JLex|null
     */
    private static $JLex = null;

    protected $regPunc = '/(?:\>\>\>\=|\>\>\>|\<\<\=|\>\>\=|\!\=\=|\=\=\=|&&|\<\<|\>\>|\|\||\*\=|\|\=|\^\=|&\=|%\=|-\=|\+\+|\+\=|--|\=\=|\>\=|\!\=|\<\=|;|,|\<|\>|\.|\]|\}|\(|\)|\[|\=|\:|\||&|-|\{|\^|\!|\?|\*|%|~|\+)/';

    function __construct(bool $whitespace, bool $unicode)
    {
        parent::__construct($whitespace, $unicode);

        $this->Lex = self::getJLex();
    }

    private static function getJLex() : JLex
    {
        if(!isset(self::$JLex)) {
            self::$JLex = new JLex();
        }

        return self::$JLex;
    }

    public static function getTokens(string $src, bool $whitespace = true, bool $unicode = true) : array
    {
        $Tokenizer = new JTokenizer($whitespace, $unicode);

        return $Tokenizer->get_all_tokens($src);
    }

    public static function getTokenName($t)
    {
        return self::getJLex()->name($t);
    }
}
