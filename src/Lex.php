<?php

namespace JTokenizer;

abstract class Lex
{
    private static $singletons = array();
    protected $i;
    protected $names = array(P_EPSILON => 'P_EPSILON', P_EOF => 'P_EOF', P_GOAL => 'P_GOAL',);
    protected $literals = array();

    function __construct($i = null)
    {
        if (!is_null($i)) {
            $this->i = (int)$i;
        }
    }

    function destroy()
    {
        $class = get_class($this);
        unset(self::$singletons[$class]);
        unset($this->names);
        unset($this->literals);
    }

    static function get($class)
    {
        $class = '\JTokenizer\\'.$class;

        if (!isset(self::$singletons[$class])) {
            self::$singletons[$class] = new $class;
        }

        return self::$singletons[$class];
    }

    function defined($c)
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

    function name($i)
    {
        if (is_int($i)) {
            if (!isset($this->names[$i])) {
                trigger_error("symbol " . var_export($i, true) . " is unknown in " . get_class($this), E_USER_NOTICE);

                return 'UNKNOWN';
            } else {
                return $this->names[$i];
            }
        } else {
            if (!isset($this->literals[$i])) {
                trigger_error("literal symbol " . var_export($i, true) . " is unknown in " . get_class($this), E_USER_NOTICE);
            }
        }

        return $i;
    }

    function implode($s, array $a)
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
