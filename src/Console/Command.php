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

namespace AndreasWeber\PHPJUNITMERGE\Console;

use Symfony\Component\Console\Command\Command as AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use TheSeer\fDOM\fDOMDocument;

/**
 * Base command class.
 *
 * @author    Andreas Weber <code@andreas-weber.me>
 * @copyright 2015 Andreas Weber <code@andreas-weber.me>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/andreas-weber/php-junit-merge
 * @since     Class available since Release 1.0.0
 */
class Command extends AbstractCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('phpjunitmerge')
            ->setDefinition(
                array(
                    new InputArgument(
                        'dir',
                        InputArgument::REQUIRED,
                        'Directory where all files ready to get merged are stored'
                    ),
                    new InputArgument(
                        'file',
                        InputArgument::REQUIRED,
                        'The target file in which the merged result should be written'
                    )
                )
            )
            ->addOption(
                'names',
                null,
                InputOption::VALUE_REQUIRED,
                'A comma-separated list of file names to check',
                array('*.xml')
            )
            ->addOption(
                'ignore',
                null,
                InputOption::VALUE_REQUIRED,
                'A comma-separated list of file names to ignore',
                array('result.xml')
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|integer null or 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('dir');
        $fileOut = $input->getArgument('file');
        $names = $input->getOption('names');
        $ignoreNames = $input->getOption('ignore');

        // here is where the magic happens
        $files = $this->findFiles($directory, $names, $ignoreNames);
        $outXml = $this->mergeFiles(realpath($directory), $files);
        $result = $this->writeFile($outXml, $fileOut);

        $output->writeln(
            '<info>Found and processed ' . count($files) . ' files. Wrote merged result in \'' . $fileOut . '\'.</info>'
        );

        return (true === $result) ? 0 : 1;
    }

    /**
     * Find all files to merge.
     *
     * @param string $directory
     * @param array  $names
     * @param array  $ignoreNames
     *
     * @return Finder
     */
    protected function findFiles($directory, array $names, array $ignoreNames)
    {
        $finder = new Finder();
        $finder->files()->in($directory);

        foreach ($names as $name) {
            $finder->name($name);
        }

        foreach ($ignoreNames as $name) {
            $finder->notName($name);
        }

        $finder->sortByName();

        return $finder;
    }

    /**
     * Merge all files.
     *
     * @param string $directory
     * @param Finder $finder
     *
     * @return fDOMDocument
     */
    protected function mergeFiles($directory, Finder $finder)
    {
        $outXml = new fDOMDocument;
        $outXml->formatOutput = true;

        $outTestSuites = $outXml->createElement('testsuites');
        $outXml->appendChild($outTestSuites);

        $outTestSuite = $outXml->createElement('testsuite');
        $outTestSuites->appendChild($outTestSuite);

        $tests = 0;
        $assertions = 0;
        $failures = 0;
        $errors = 0;
        $time = 0;

        foreach ($finder as $file) {
            $inXml = $this->loadFile($file->getRealpath());
            foreach ($inXml->query('//testsuites/testsuite') as $inElement) {
                $outElement = $outXml->importNode($inElement, true);
                $outTestSuite->appendChild($outElement);

                $tests += $inElement->getAttribute('tests');
                $assertions += $inElement->getAttribute('assertions');
                $failures += $inElement->getAttribute('failures');
                $errors += $inElement->getAttribute('errors');
                $time += $inElement->getAttribute('time');
            }
        }

        $outTestSuite->setAttribute('name', $directory);
        $outTestSuite->setAttribute('tests', $tests);
        $outTestSuite->setAttribute('assertions', $assertions);
        $outTestSuite->setAttribute('failures', $failures);
        $outTestSuite->setAttribute('errors', $errors);
        $outTestSuite->setAttribute('time', $time);

        return $outXml;
    }

    /**
     * Load an xml junit file.
     *
     * @param string $filename
     *
     * @return fDOMDOcument
     */
    protected function loadFile($filename)
    {
        $dom = new fDOMDocument();
        $dom->load($filename);

        return $dom;
    }

    /**
     * Writes the merged result file.
     *
     * @param fDOMDocument $dom
     * @param string       $filename
     *
     * @return bool
     */
    protected function writeFile(fDOMDocument $dom, $filename)
    {
        $dom->formatOutput = true;
        $result = $dom->save($filename);

        return ($result !== false) ? true : false;
    }
}
