<?php

namespace JTokenizer;

abstract class JTokenizerBase
{
    const ERROR_NOTHING_EXTRACTED = 81601;

    /**
     * @var int
     */
    private $line;

    /**
     * @var int
     */
    private $col;

    /**
     * @var bool
     */
    private $divmode;

    /**
     * @var string
     */
    private $src = '';

    /**
     * @var bool
     */
    private $whitespace;

    /**
     * @var bool
     */
    private $unicode;

    private $regRegex = '!^/(?:\\\\.|[^\r\n/\\\\])+/[gi]*!';
    private $regDQuote = '/^"(?:\\\\(?:.|\r\n)|[^\r\n"\\\\])*"/s';
    private $regSQuote = "/^'(?:\\\\(?:.|\r\n)|[^\r\n'\\\\])*'/s";
    private $regWord = '/^[\$_A-Z][\$_A-Z0-9]*/i';
    private $regWhite = '/^[\x20\x09\x0B\x0C\xA0]+/';
    private $regBreak = '/^[\r\n]+/';
    private $regJunk = '/^./';
    private $regLines = '/(\r\n|\r|\n)/';
    private $regNumber = '/^(?:0x[A-F0-9]+|\d*\.\d+(?:E(?:\+|\-)?\d+)?|\d+)/i';
    private $regComment = '/^\/\/.*/';
    private $regCommentMulti = '/^\/\*.*\*\//Us';

    /**
     * @var JMessage[]
     */
    private $messages = array();

    /**
     * @var string
     */
    protected $regPunc = '';

    /**
     * @var JLexBase
     */
    protected $Lex;

    function __construct(bool $whitespace, bool $unicode)
    {
        $this->whitespace = $whitespace;
        $this->unicode = $unicode;

        if ($this->unicode) {
            $this->useUnicodeRegexes();
        }
    }

    /**
     * Adjusts all regexes in case the source is set to be unicode.
     */
    private function useUnicodeRegexes() : void
    {
        $this->regRegex = '!^/(?:\\\\.|[^\r\n\p{Zl}\p{Zp}/\\\\])+/[gi]*!u';
        $this->regDQuote = '/^"(?:\\\\(?:.|\r\n)|[^\r\n\p{Zl}\p{Zp}"\\\\])*"/su';
        $this->regSQuote = "/^'(?:\\\\(?:.|\r\n)|[^\r\n\p{Zl}\p{Zp}'\\\\])*'/su";
        $this->regWord = '/^(?:\\\\u[0-9A-F]{4,4}|[\$_\pL\p{Nl}])(?:\\\\u[0-9A-F]{4,4}|[\$_\pL\pN\p{Mn}\p{Mc}\p{Pc}])*/ui';
        $this->regWhite = '/^[\x20\x09\x0B\x0C\xA0\p{Zs}]+/u';
        $this->regBreak = '/^[\r\n\p{Zl}\p{Zp}]+/u';
        $this->regJunk = '/^./u';
        $this->regLines = '/(\r\n|[\r\n\p{Zl}\p{Zp}])/u';
    }

    function init(string $src) : void
    {
        $this->src = $src;
        $this->line = 1;
        $this->col = 1;
        $this->divmode = false;
        $this->messages = array();
    }

    function get_all_tokens(string $src) : array
    {
        $this->init($src);

        $tokens = array();
        while ($this->src) {
            $token = $this->get_next_token() and $tokens[] = $token;
        }

        return $tokens;
    }

    /**
     * @return array<mixed>|null
     * @throws JException
     */
    private function get_next_token() : ?array
    {
        $r = null;

        $c = $this->src[0];
        if ($c === '"') {
            if (!preg_match($this->regDQuote, $this->src, $r)) {
                $this->addMessage("Unterminated string constant on line $this->line", E_USER_NOTICE);
                $literal = $identifier = '"';
            } else {
                $literal = $r[0];
                $identifier = J_STRING_LITERAL;
            }
            $this->divmode = true;
        } else if ($c === "'") {
            if (!preg_match($this->regSQuote, $this->src, $r)) {
                $this->addMessage("Unterminated string constant on line $this->line", E_USER_NOTICE);
                $literal = $identifier = "'";
            } else {
                $literal = $r[0];
                $identifier = J_STRING_LITERAL;
            }
            $this->divmode = true;
        } else {
            if ($c === '/') {
                if ($this->src[1] === '/' && preg_match($this->regComment, $this->src, $r)) {
                    $identifier = $this->whitespace ? J_COMMENT : false;
                    $literal = $r[0];
                } else {
                    if ($this->src[1] === '*' && preg_match($this->regCommentMulti, $this->src, $r)) {
                        $literal = $r[0];
                        if ($this->whitespace) {
                            $identifier = J_COMMENT;
                        } else {
                            $breaks = preg_match($this->regLines, $literal, $r);
                            $identifier = $breaks ? J_LINE_TERMINATOR : false;
                        }
                    } else {
                        if (!$this->divmode) {
                            if (!preg_match($this->regRegex, $this->src, $r)) {
                                $this->addMessage("Bad regular expression literal on line $this->line", E_USER_NOTICE);
                                $literal = $identifier = '/';
                                $this->divmode = false;
                            } else {
                                $literal = $r[0];
                                $identifier = J_REGEX;
                                $this->divmode = true;
                            }
                        } else {
                            if ($this->src[1] === '=') {
                                $literal = $identifier = '/=';
                                $this->divmode = false;
                            } else {
                                $literal = $identifier = '/';
                                $this->divmode = false;
                            }
                        }
                    }
                }
            } else {
                if (preg_match($this->regBreak, $this->src, $r)) {
                    $identifier = J_LINE_TERMINATOR;
                    $literal = $r[0];
                    $this->divmode = false;
                } else {
                    if (preg_match($this->regWhite, $this->src, $r)) {
                        $identifier = $this->whitespace ? J_WHITESPACE : false;
                        $literal = $r[0];
                    } else {
                        if (preg_match($this->regNumber, $this->src, $r)) {
                            $identifier = J_NUMERIC_LITERAL;
                            $literal = $r[0];
                            $this->divmode = true;
                        } else {
                            if (preg_match($this->regWord, $this->src, $r)) {
                                $literal = $r[0];
                                $identifier = $this->Lex->is_word($literal) or $identifier = J_IDENTIFIER;
                                switch ($identifier) {
                                    case J_IDENTIFIER;
                                        $this->divmode = true;
                                        break;
                                    default:
                                        $this->divmode = null;
                                }
                            } else {
                                if (preg_match($this->regPunc, $this->src, $r)) {
                                    $literal = $identifier = $r[0];
                                    switch ($identifier) {
                                        case ']':
                                        case ')':
                                            $this->divmode = true;
                                            break;
                                        default:
                                            $this->divmode = false;
                                    }
                                } else {
                                    preg_match($this->regJunk, $this->src, $r);
                                    $literal = $identifier = $r[0];
                                    $this->addMessage("Junk on line $this->line, $literal", E_USER_NOTICE);
                                }
                            }
                        }
                    }
                }
            }
        }
        $len = strlen($literal);
        if ($len === 0) {
            throw new JException(
                'Failed to extract anything',
                self::ERROR_NOTHING_EXTRACTED
            );
        }
        if ($identifier !== false) {
            $token = array(
                $identifier,
                $literal,
                $this->line,
                $this->col
            );
        }

        $this->src = substr($this->src, $len);

        if ($identifier === J_LINE_TERMINATOR || $identifier === J_COMMENT) {
            $this->line += preg_match_all($this->regLines, $literal, $r);
            $cbreak = chr((int)end($r[0]));

            $this->col = $len - strrpos($literal, $cbreak);
        } else {
            $this->col += $len;
        }

        if(isset($token)) {
            return $token;
        }

        return null;
    }

    private function addMessage(string $string, int $level) : void
    {
        $this->messages[] = new JMessage($string, $level);
    }

    /**
     * @return JMessage[]
     */
    public function getMessages() : array
    {
        return $this->messages;
    }

    /**
     * @return bool
     */
    public function hasMessages() : bool
    {
        return !empty($this->messages);
    }
}
