<?php

namespace JTokenizer;

abstract class JTokenizerBase
{
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
    protected $regPunc;
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

    function get_next_token()
    {
        $r = null;

        $c = $this->src[0];
        if ($c === '"') {
            if (!preg_match($this->regDQuote, $this->src, $r)) {
                trigger_error("Unterminated string constant on line $this->line", E_USER_NOTICE);
                $s = $t = '"';
            } else {
                $s = $r[0];
                $t = J_STRING_LITERAL;
            }
            $this->divmode = true;
        } else if ($c === "'") {
            if (!preg_match($this->regSQuote, $this->src, $r)) {
                trigger_error("Unterminated string constant on line $this->line", E_USER_NOTICE);
                $s = $t = "'";
            } else {
                $s = $r[0];
                $t = J_STRING_LITERAL;
            }
            $this->divmode = true;
        } else {
            if ($c === '/') {
                if ($this->src[1] === '/' && preg_match($this->regComment, $this->src, $r)) {
                    $t = $this->whitespace ? J_COMMENT : false;
                    $s = $r[0];
                } else {
                    if ($this->src[1] === '*' && preg_match($this->regCommentMulti, $this->src, $r)) {
                        $s = $r[0];
                        if ($this->whitespace) {
                            $t = J_COMMENT;
                        } else {
                            $breaks = preg_match($this->regLines, $s, $r);
                            $t = $breaks ? J_LINE_TERMINATOR : false;
                        }
                    } else {
                        if (!$this->divmode) {
                            if (!preg_match($this->regRegex, $this->src, $r)) {
                                trigger_error("Bad regular expression literal on line $this->line", E_USER_NOTICE);
                                $s = $t = '/';
                                $this->divmode = false;
                            } else {
                                $s = $r[0];
                                $t = J_REGEX;
                                $this->divmode = true;
                            }
                        } else {
                            if ($this->src[1] === '=') {
                                $s = $t = '/=';
                                $this->divmode = false;
                            } else {
                                $s = $t = '/';
                                $this->divmode = false;
                            }
                        }
                    }
                }
            } else {
                if (preg_match($this->regBreak, $this->src, $r)) {
                    $t = J_LINE_TERMINATOR;
                    $s = $r[0];
                    $this->divmode = false;
                } else {
                    if (preg_match($this->regWhite, $this->src, $r)) {
                        $t = $this->whitespace ? J_WHITESPACE : false;
                        $s = $r[0];
                    } else {
                        if (preg_match($this->regNumber, $this->src, $r)) {
                            $t = J_NUMERIC_LITERAL;
                            $s = $r[0];
                            $this->divmode = true;
                        } else {
                            if (preg_match($this->regWord, $this->src, $r)) {
                                $s = $r[0];
                                $t = $this->Lex->is_word($s) or $t = J_IDENTIFIER;
                                switch ($t) {
                                    case J_IDENTIFIER;
                                        $this->divmode = true;
                                        break;
                                    default:
                                        $this->divmode = null;
                                }
                            } else {
                                if (preg_match($this->regPunc, $this->src, $r)) {
                                    $s = $t = $r[0];
                                    switch ($t) {
                                        case ']':
                                        case ')':
                                            $this->divmode = true;
                                            break;
                                        default:
                                            $this->divmode = false;
                                    }
                                } else {
                                    preg_match($this->regJunk, $this->src, $r);
                                    $s = $t = $r[0];
                                    trigger_error("Junk on line $this->line, $s", E_USER_NOTICE);
                                }
                            }
                        }
                    }
                }
            }
        }
        $len = strlen($s);
        if ($len === 0) {
            throw new \Exception('Failed to extract anything');
        }
        if ($t !== false) {
            $token = array($t, $s, $this->line, $this->col);
        }
        $this->src = substr($this->src, $len);
        if ($t === J_LINE_TERMINATOR || $t === J_COMMENT) {
            $this->line += preg_match_all($this->regLines, $s, $r);
            $cbreak = chr((int)end($r[0]));

            $this->col = $len - strrpos($s, $cbreak);
        } else {
            $this->col += $len;
        }

        return isset($token) ? $token : null;
    }
}
