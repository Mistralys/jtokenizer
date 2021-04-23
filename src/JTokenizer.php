<?php
/**
 * File containing the class {@see JTokenizer}.
 *
 * @package JTokenizer
 * @see JTokenizer
 */

namespace JTokenizer;

/**
 * The tokenizer main class: offers static methods to tokenize
 * JavaScript source code.
 *
 * @package JTokenizer
 * @author Tim Whitlock
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
class JTokenizer extends JTokenizerBase
{
    const ERROR_FILE_CANNOT_BE_READ = 81801;

    /**
     * @var JLex|null
     */
    private static $JLex = null;

    /**
     * @var string 
     */
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

    /**
     * Parses the target javascript file, and returns all tokens.
     *
     * @param string $path
     * @param bool $whitespace
     * @param bool $unicode
     * @return array<mixed>
     * @throws JException
     */
    public static function getFileTokens(string $path, bool $whitespace=true, bool $unicode=true) : array
    {
        $content = file_get_contents($path);

        if($content !== false) {
            return self::getTokens($content, $whitespace, $unicode);
        }

        throw new JException(
            'Could not get contents from target file [%s].',
            self::ERROR_FILE_CANNOT_BE_READ
        );
    }

    /**
     *
     * Returns an indexed array with a list of token arrays. Each
     * token array is an indexed array with the following information:
     *
     * - Token identifier
     * - Matched literal
     * - Line number
     * - Column number
     *
     * @param string $src
     * @param bool $whitespace
     * @param bool $unicode
     * @return array
     */
    public static function getTokens(string $src, bool $whitespace = true, bool $unicode = true) : array
    {
        $Tokenizer = new JTokenizer($whitespace, $unicode);

        return $Tokenizer->get_all_tokens($src);
    }

    /**
     * @param int|string $identifier
     * @return string
     * @throws JException
     */
    public static function getTokenName($identifier) : string
    {
        return self::getJLex()->name($identifier);
    }
}
