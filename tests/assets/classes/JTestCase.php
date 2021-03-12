<?php
/**
 * File containing the class {@see JTestCase}.
 *
 * @package JTokenizer
 * @subpackage Tests
 * @see JTestCase
 */

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Base test case class with common functionality for the test suites.
 *
 * @package JTokenizer
 * @subpackage Tests
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
abstract class JTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $assetsFolder;

    protected function setUp(): void
    {
        $this->assetsFolder = JTESTS_ROOT.'/assets/files';
    }
}
