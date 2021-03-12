<?php
/**
 * Bootstrapper file for the test suites: Loads the test
 * specific classes and defines paths. Loaded automatically
 * by PHPUnit.
 *
 * @package JTokenizer
 * @subpackage Tests
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */

    /**
     * The root of the test suites.
     */
    define('JTESTS_ROOT', __DIR__);

    require_once __DIR__.'/assets/classes/JTestCase.php';
