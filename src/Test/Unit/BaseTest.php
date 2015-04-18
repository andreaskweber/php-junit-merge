<?php
/**
 * phpjunitmerge
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015, Andreas Weber <code@andreas-weber.me>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE
 *
 * @package   phpjunitmerge
 * @author    Andreas Weber <code@andreas-weber.me>
 * @copyright 2015 Andreas Weber <code@andreas-weber.me>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @since     File available since Release 1.0.0
 */

namespace AndreasWeber\PHPJUNITMERGE\Tests\Unit;

use AndreasWeber\PHPJUNITMERGE\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use TheSeer\fDOM\fDOMDOcument;

/**
 * Base tests class.
 *
 * @author    Andreas Weber <code@andreas-weber.me>
 * @copyright 2015 Andreas Weber <code@andreas-weber.me>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/andreas-weber/php-junit-merge
 * @since     Class available since Release 1.0.0
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    private $fixtures;
    private $resultFile;
    private $application;
    private $input;
    private $output;
    private $statusCode;

    /**
     * setUp()
     */
    protected function setUp()
    {
        $this->fixtures = realpath(__DIR__ . '/Fixtures');
        $this->resultFile = realpath($this->fixtures . '/../tmp') . '/tmp.xml';
        $this->application = new Application();
    }

    /**
     * Tests if merging multiple files works as expected.
     */
    public function testIfMergingWorks()
    {
        $fixtures = $this->fixtures . '/normal';

        $params = array(
            'dir' => $fixtures,
            'file' => $this->resultFile
        );

        $this->runApplication($params);

        $expected = $this->loadFile($fixtures . '/result.xml');
        $actual = $this->loadFile($this->resultFile);

        $this->assertEquals(
            $expected->__toString(),
            $actual->__toString()
        );
    }

    /**
     * Tests if merging multiple files works as expected.
     * When an empty file is given, it should be skipped.
     */
    public function testIfMergingWithEmptyFilesWorks()
    {
        $fixtures = $this->fixtures . '/empty-file';

        $params = array(
            'dir' => $fixtures,
            'file' => $this->resultFile
        );

        $this->runApplication($params);

        $expected = $this->loadFile($fixtures . '/result.xml');
        $actual = $this->loadFile($this->resultFile);

        $this->assertEquals(
            $expected->__toString(),
            $actual->__toString()
        );
    }

    /**
     * Load a test file.
     *
     * @param string $filename
     *
     * @return fDOMDocument
     */
    private function loadFile($filename)
    {
        $dom = new fDOMDOcument();
        $dom->load($filename);

        return $dom;
    }

    /**
     * Executes the application.
     *
     * @param array $input An array of arguments and options
     *
     * @return int The command exit code
     */
    public function runApplication(array $input)
    {
        $this->input = new ArrayInput($input);

        return $this->statusCode = $this->application->run($this->input, $this->output);
    }
}
