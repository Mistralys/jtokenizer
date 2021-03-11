<?php

namespace JTokenizer;

class JLexBase extends Lex
{
    protected $i = 142;
    protected $names = array(-3 => 'P_GOAL', -2 => 'P_EOF', -1 => 'P_EPSILON', 1 => 'J_FUNCTION', 2 => 'J_IDENTIFIER', 3 => 'J_VAR', 4 => 'J_IF', 5 => 'J_ELSE', 6 => 'J_DO', 7 => 'J_WHILE', 8 => 'J_FOR', 9 => 'J_IN', 10 => 'J_CONTINUE', 11 => 'J_BREAK', 12 => 'J_RETURN', 13 => 'J_WITH', 14 => 'J_SWITCH', 15 => 'J_CASE', 16 => 'J_DEFAULT', 17 => 'J_THROW', 18 => 'J_TRY', 19 => 'J_CATCH', 20 => 'J_FINALLY', 21 => 'J_THIS', 22 => 'J_STRING_LITERAL', 23 => 'J_NUMERIC_LITERAL', 24 => 'J_TRUE', 25 => 'J_FALSE', 26 => 'J_NULL', 27 => 'J_REGEX', 28 => 'J_NEW', 29 => 'J_DELETE', 30 => 'J_VOID', 31 => 'J_TYPEOF', 32 => 'J_INSTANCEOF', 33 => 'J_COMMENT', 34 => 'J_WHITESPACE', 35 => 'J_LINE_TERMINATOR', 36 => 'J_ABSTRACT', 37 => 'J_ENUM', 38 => 'J_INT', 39 => 'J_SHORT', 40 => 'J_BOOLEAN', 41 => 'J_EXPORT', 42 => 'J_INTERFACE', 43 => 'J_STATIC', 44 => 'J_BYTE', 45 => 'J_EXTENDS', 46 => 'J_LONG', 47 => 'J_SUPER', 48 => 'J_CHAR', 49 => 'J_FINAL', 50 => 'J_NATIVE', 51 => 'J_SYNCHRONIZED', 52 => 'J_CLASS', 53 => 'J_FLOAT', 54 => 'J_PACKAGE', 55 => 'J_THROWS', 56 => 'J_CONST', 57 => 'J_GOTO', 58 => 'J_PRIVATE', 59 => 'J_TRANSIENT', 60 => 'J_DEBUGGER', 61 => 'J_IMPLEMENTS', 62 => 'J_PROTECTED', 63 => 'J_VOLATILE', 64 => 'J_DOUBLE', 65 => 'J_IMPORT', 66 => 'J_PUBLIC', 67 => 'J_PROGRAM', 68 => 'J_ELEMENTS', 69 => 'J_ELEMENT', 70 => 'J_STATEMENT', 71 => 'J_FUNC_DECL', 72 => 'J_PARAM_LIST', 73 => 'J_FUNC_BODY', 74 => 'J_FUNC_EXPR', 75 => 'J_BLOCK', 76 => 'J_VAR_STATEMENT', 77 => 'J_EMPTY_STATEMENT', 78 => 'J_EXPR_STATEMENT', 79 => 'J_IF_STATEMENT', 80 => 'J_ITER_STATEMENT', 81 => 'J_CONT_STATEMENT', 82 => 'J_BREAK_STATEMENT', 83 => 'J_RETURN_STATEMENT', 84 => 'J_WITH_STATEMENT', 85 => 'J_LABELLED_STATEMENT', 86 => 'J_SWITCH_STATEMENT', 87 => 'J_THROW_STATEMENT', 88 => 'J_TRY_STATEMENT', 89 => 'J_STATEMENT_LIST', 90 => 'J_VAR_DECL_LIST', 91 => 'J_VAR_DECL', 92 => 'J_VAR_DECL_LIST_NO_IN', 93 => 'J_VAR_DECL_NO_IN', 94 => 'J_INITIALIZER', 95 => 'J_INITIALIZER_NO_IN', 96 => 'J_ASSIGN_EXPR', 97 => 'J_ASSIGN_EXPR_NO_IN', 98 => 'J_EXPR', 99 => 'J_EXPR_NO_IN', 100 => 'J_LHS_EXPR', 101 => 'J_CASE_BLOCK', 102 => 'J_CASE_CLAUSES', 103 => 'J_CASE_DEFAULT', 104 => 'J_CASE_CLAUSE', 105 => 'J_CATCH_CLAUSE', 106 => 'J_FINALLY_CLAUSE', 107 => 'J_PRIMARY_EXPR', 108 => 'J_ARRAY_LITERAL', 109 => 'J_OBJECT_LITERAL', 110 => 'J_ELISION', 111 => 'J_ELEMENT_LIST', 112 => 'J_PROP_LIST', 113 => 'J_PROP_NAME', 114 => 'J_MEMBER_EXPR', 115 => 'J_ARGS', 116 => 'J_NEW_EXPR', 117 => 'J_CALL_EXPR', 118 => 'J_ARG_LIST', 119 => 'J_POSTFIX_EXPR', 120 => 'J_UNARY_EXPR', 121 => 'J_MULT_EXPR', 122 => 'J_ADD_EXPR', 123 => 'J_SHIFT_EXPR', 124 => 'J_REL_EXPR', 125 => 'J_REL_EXPR_NO_IN', 126 => 'J_EQ_EXPR', 127 => 'J_EQ_EXPR_NO_IN', 128 => 'J_BIT_AND_EXPR', 129 => 'J_BIT_AND_EXPR_NO_IN', 130 => 'J_BIT_XOR_EXPR', 131 => 'J_BIT_XOR_EXPR_NO_IN', 132 => 'J_BIT_OR_EXPR', 133 => 'J_BIT_OR_EXPR_NO_IN', 134 => 'J_LOG_AND_EXPR', 135 => 'J_LOG_AND_EXPR_NO_IN', 136 => 'J_LOG_OR_EXPR', 137 => 'J_LOG_OR_EXPR_NO_IN', 138 => 'J_COND_EXPR', 139 => 'J_COND_EXPR_NO_IN', 140 => 'J_ASSIGN_OP', 141 => 'J_IGNORE', 142 => 'J_RESERVED',);
    protected $literals = array('(' => 1, ')' => 1, '{' => 1, '}' => 1, ',' => 1, ';' => 1, '=' => 1, ':' => 1, '[' => 1, ']' => 1, '.' => 1, '++' => 2, '--' => 2, '+' => 1, '-' => 1, '~' => 1, '!' => 1, '*' => 1, '/' => 1, '%' => 1, '<<' => 2, '>>' => 2, '>>>' => 3, '<' => 1, '>' => 1, '<=' => 2, '>=' => 2, '==' => 2, '!=' => 2, '===' => 3, '!==' => 3, '&' => 1, '^' => 1, '|' => 1, '&&' => 2, '||' => 2, '?' => 1, '*=' => 2, '/=' => 2, '%=' => 2, '+=' => 2, '-=' => 2, '<<=' => 3, '>>=' => 3, '>>>=' => 4, '&=' => 2, '^=' => 2, '|=' => 2,);
}
