<?php

namespace JTokenizer;

abstract class Lex
{
    const ERROR_UNKNOWN_SYMBOL = 81701;
    const ERROR_UNKNOWN_LITERAL_SYMBOL = 81702;

    /**
     * @var int
     */
    protected $i;

    /**
     * @var array<int,string>
     */
    protected $names = array(
        P_EPSILON => 'P_EPSILON',
        P_EOF => 'P_EOF',
        P_GOAL => 'P_GOAL'
    );

    /**
     * @var array<string,int>
     */
    protected $literals = array();

    function __construct(int $i = 0)
    {
        $this->i = $i;
    }

    public function destroy() : void
    {
        unset($this->names);
        unset($this->literals);
    }

    function defined($c) : bool
    {
        if (isset($this->literals[$c])) {
            return true;
        }
        if (!defined($c)) {
            return false;
        }
        $i = constant($c);

        return isset($this->names[$i]) && $this->names[$i] === $c;
    }

    /**
     * @param string|int $i
     * @return string
     * @throws JException
     *
     * @see Lex::ERROR_UNKNOWN_LITERAL_SYMBOL
     * @see Lex::ERROR_UNKNOWN_SYMBOL
     */
    function name($i) : string
    {
        if (is_int($i))
        {
            if (isset($this->names[$i])) {
                return $this->names[$i];
            }

            throw new JException(
                "The symbol " . var_export($i, true) . " is unknown in " . get_class($this),
                self::ERROR_UNKNOWN_SYMBOL
            );
        }

        $literal = strval($i);

        if (isset($this->literals[$literal])) {
            return $literal;
        }

        throw new JException(
            sprintf(
                "The literal symbol [%s] is unknown in [%s].",
                $literal,
                get_class($this)
            ),
            self::ERROR_UNKNOWN_LITERAL_SYMBOL
        );
    }

    function implode($s, array $a) : string
    {
        $b = array();
        foreach ($a as $t) {
            $b[] = $this->name($t);
        }

        return implode($s, $b);
    }

    function dump()
    {
        asort($this->names, SORT_STRING);
        $t = max(2, strlen((string)$this->i));
        foreach ($this->names as $i => $n) {
            $i = str_pad($i, $t, ' ', STR_PAD_LEFT);
            echo "$i => $n \n";
        }
    }
}
