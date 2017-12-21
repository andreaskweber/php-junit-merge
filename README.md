# andreas-weber/php-junit-merge

[![Build Status](https://travis-ci.org/andreas-weber/php-junit-merge.svg?branch=master)](https://travis-ci.org/andreas-weber/php-junit-merge)

**php-junit-merge** is a library that merges multiple junit result xml files.

## Installation

Simply add a dependency on `andreas-weber/php-junit-merge` to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project.

## Usage

After updating dependencies by composer a new binary `php-junit-merge` is available for usage.

    root@dev:~/projects/sample/vendor/bin ./phpjunitmerge
    phpjunitmerge 1.0.4 by Andreas Weber
    
    Usage:
     phpjunitmerge [--names="..."] [--ignore="..."] dir file
    
    Arguments:
     dir                   Directory where all files ready to get merged are stored
     file                  The target file in which the merged result should be written
    
    Options:
     --names               A comma-separated list of file names to check (default: "*.xml")
     --ignore              A comma-separated list of file names to ignore (default: "result.xml")
     --help (-h)           Display this help message
     --quiet (-q)          Do not output any message
     --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
     --version (-V)        Display this application version
     --ansi                Force ANSI output
     --no-ansi             Disable ANSI output
     --no-interaction (-n) Do not ask any interactive question
     --no-suffix           Do not add suffix for test suites with duplicate names


The binary expects at least two parameters:

- `dir` is the directory, where the application should search for xml files
- `file` is the result file, in which the application should write the merged content

A simple call could look like this:

    root@dev:~/projects/sample/vendor/bin ./phpjunitmerge src/Tests/Unit/Fixtures result.xml
    phpjunitmerge 1.0.0 by Andreas Weber
    
    Found and processed 3 files. Wrote merged content in 'result.xml'.
    
## Example

### Single Result Files

    <?xml version="1.0" encoding="UTF-8"?>
    <testsuites>
        <testsuite name="/Some_PHPUnit_Testsuite1" tests="2" assertions="2" failures="0" errors="0" time="1.234567">
            <testsuite name="Unit\Testsuite2" file="/Unit/Testsuite1.php" tests="2" assertions="2" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
            </testsuite>
        </testsuite>
    </testsuites>

    <?xml version="1.0" encoding="UTF-8"?>
    <testsuites>
        <testsuite name="/Some_PHPUnit_Testsuite2" tests="3" assertions="3" failures="0" errors="0" time="1.234567">
            <testsuite name="Unit\Testsuite1" file="/Unit/Testsuite1.php" tests="3" assertions="3" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
                <testcase name="someRandomTestName3" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="18" assertions="1" time="0.000825"/>
            </testsuite>
        </testsuite>
    </testsuites>

    <?xml version="1.0" encoding="UTF-8"?>
    <testsuites>
        <testsuite name="/Some_PHPUnit_Testsuite3" tests="6" assertions="6" failures="0" errors="0" time="1.234567">
            <testsuite name="Unit\Testsuite3" file="/Unit/Testsuite1.php" tests="2" assertions="2" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
            </testsuite>
            <testsuite name="Unit\Testsuite4" file="/Unit/Testsuite1.php" tests="4" assertions="4" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
                <testcase name="someRandomTestName3" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="16" assertions="1" time="0.000795"/>
                <testcase name="someRandomTestName4" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="21" assertions="1" time="0.000795"/>
            </testsuite>
        </testsuite>
    </testsuites>
    
### Merged Result File

    <?xml version="1.0" encoding="utf-8"?>
    <testsuites>
      <testsuite tests="11" assertions="11" failures="0" errors="0" time="3.703701">
        <testsuite name="/Some_PHPUnit_Testsuite1" tests="2" assertions="2" failures="0" errors="0" time="1.234567">
            <testsuite name="Unit\Testsuite2" file="/Unit/Testsuite1.php" tests="2" assertions="2" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
            </testsuite>
        </testsuite>
        <testsuite name="/Some_PHPUnit_Testsuite2" tests="3" assertions="3" failures="0" errors="0" time="1.234567">
            <testsuite name="Unit\Testsuite1" file="/Unit/Testsuite1.php" tests="3" assertions="3" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
                <testcase name="someRandomTestName3" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="18" assertions="1" time="0.000825"/>
            </testsuite>
        </testsuite>
        <testsuite name="/Some_PHPUnit_Testsuite3" tests="6" assertions="6" failures="0" errors="0" time="1.234567">
            <testsuite name="Unit\Testsuite3" file="/Unit/Testsuite1.php" tests="2" assertions="2" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
            </testsuite>
            <testsuite name="Unit\Testsuite4" file="/Unit/Testsuite1.php" tests="4" assertions="4" failures="0" errors="0" time="0.003623">
                <testcase name="someRandomTestName1" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="8" assertions="1" time="0.002003"/>
                <testcase name="someRandomTestName2" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="13" assertions="1" time="0.000795"/>
                <testcase name="someRandomTestName3" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="16" assertions="1" time="0.000795"/>
                <testcase name="someRandomTestName4" class="Unit\Testsuite1" file="/Unit/Testsuite1.php" line="21" assertions="1" time="0.000795"/>
            </testsuite>
        </testsuite>
      </testsuite>
    </testsuites>
    
## Developer

### Environment

Boot:

```
vagrant up
```

Enter virtual machine:

```
vagrant ssh
```

Run tests:

```
cd /vagrant
vendor/bin/phpunit src/Test/
```

### Build targets

```
vagrant@andreas-weber:/vagrant$ ant
Buildfile: /vagrant/build.xml

help:
     [echo]
     [echo] The following commands are available:
     [echo]
     [echo] |   +++ Build +++
     [echo] |-- build                (Run the build)
     [echo] |   |-- dependencies     (Install dependencies)
     [echo] |   |-- tests            (Lint all files and run tests)
     [echo] |   |-- metrics          (Generate quality metrics)
     [echo] |-- cleanup              (Cleanup the build directory)
     [echo] |
     [echo] |   +++ Composer +++
     [echo] |-- composer             -> composer-download, composer-install
     [echo] |-- composer-download    (Downloads composer.phar to project)
     [echo] |-- composer-install     (Install all dependencies)
     [echo] |
     [echo] |   +++ Testing +++
     [echo] |-- phpunit              -> phpunit-full
     [echo] |-- phpunit-tests        (Run unit tests)
     [echo] |-- phpunit-full         (Run unit tests and generate code coverage report / logs)
     [echo] |
     [echo] |   +++ Metrics +++
     [echo] |-- coverage             (Show code coverage metric)
     [echo] |-- phploc               (Show lines of code metric)
     [echo] |-- qa                   (Run quality assurance tools)
     [echo] |-- |-- phpcpd           (Show copy paste metric)
     [echo] |-- |-- phpcs            (Show code sniffer metric)
     [echo] |-- |-- phpmd            (Show mess detector metric)
     [echo] |
     [echo] |   +++ Metric Reports +++
     [echo] |-- phploc-report        (Generate lines of code metric report)
     [echo] |-- phpcpd-report        (Generate copy paste metric report)
     [echo] |-- phpcs-report         (Generate code sniffer metric report)
     [echo] |-- phpmd-report         (Generate mess detector metric report)
     [echo] |
     [echo] |   +++ Tools +++
     [echo] |-- lint                 (Lint all php files)
     [echo]
```
 
## Attributions
Thanks to [Sebastian Bergmann](https://gist.github.com/sebastianbergmann) for his gist [merge-phpunit-xml.php](https://gist.github.com/sebastianbergmann/4405658), which was the base and inspired me to develop this library.

## Thoughts
Pull requests are highly appreciated. Built with love. Hope you'll enjoy.. :-)
