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

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base application class.
 *
 * @author    Andreas Weber <code@andreas-weber.me>
 * @copyright 2015 Andreas Weber <code@andreas-weber.me>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      https://github.com/andreas-weber/php-junit-merge
 * @since     Class available since Release 1.0.0
 */
class Application extends ConsoleApplication
{
    /**
     * __construct()
     */
    public function __construct()
    {
        parent::__construct('phpjunitmerge', '1.0.7');
    }

    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input
     *
     * @return string The command name
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'phpjunitmerge';
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new Command;

        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command
     * name to be the first argument.
     *
     * @return InputDefinition
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * Runs the current application.
     *
     * @param InputInterface  $input  An Input instance
     * @param OutputInterface $output An Output instance
     *
     * @return int 0 if everything went fine, or an error code
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (!$input->hasParameterOption('--quiet')) {
            $output->write(
                sprintf(
                    "phpjunitmerge %s by Andreas Weber\n\n",
                    $this->getVersion()
                )
            );
        }

        if ($input->hasParameterOption('--version')
            || $input->hasParameterOption('-V')
        ) {
            exit;
        }

        if (!$input->getFirstArgument()) {
            $input = new ArrayInput(array('--help'));
        }

        parent::doRun($input, $output);
    }
}
