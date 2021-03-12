<?php

declare(strict_types=1);

use JTokenizer\JTokenizer;

final class TokenizerTests extends JTestCase
{
    public function test_parseFunction() : void
    {
        $file = $this->assetsFolder.'/function.js';
        $tokens = JTokenizer::getFileTokens($file);

        $this->assertIsArray($tokens);
        $this->assertSame('function', $tokens[0][1]);
    }

    public function test_parseClass() : void
    {
        $file = $this->assetsFolder.'/class.js';
        $tokens = JTokenizer::getFileTokens($file);

        $this->assertIsArray($tokens);
        $this->assertSame(J_STRING_LITERAL, $tokens[0][0]);
        $this->assertSame(J_CLASS, $tokens[3][0]);
    }
}
