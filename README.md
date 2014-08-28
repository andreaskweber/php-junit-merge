# php-junit-merge

**php-junit-merge** is a library that merges multiple junit result xml files.

## Installation

Simply add a dependency on `andreas-weber/php-junit-merge` to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project.

## Usage

After updating dependencies by composer a new binary `php-junit-merge` is available for usage.

    root@dev:~/projects/sample/vendor/bin ./phpjunitmerge
    phpjunitmerge 1.0.0 by Andreas Weber
    
    Usage:
     phpjunitmerge [--names="..."] [--ignore="..."] dir file
    
    Arguments:
     dir                   Directory where all files ready to get merged are stored
     file                  The target file in which the merged result should be written
    
    Options:
     --names               A comma-separated list of file names to check (default: ["*.xml"])
     --ignore              A comma-separated list of file names to ignore (default: ["result.xml"])
     --help (-h)           Display this help message.
     --quiet (-q)          Do not output any message.
     --verbose (-v|vv|vvv) Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
     --version (-V)        Display this application version.
     --ansi                Force ANSI output.
     --no-ansi             Disable ANSI output.
     --no-interaction (-n) Do not ask any interactive question.


The binary expects at least two parameters:

- `dir` is the directory, where the application should search for xml files
- `file` is the result file, in which the application should write the merged content

A simple call could look like this:

    root@dev:~/projects/sample/vendor/bin ./phpjunitmerge src/Tests/Unit/Fixtures result.xml
    phpjunitmerge 1.0.0 by Andreas Weber
    
    Found and processed 3 files. Wrote merged content in 'result.xml'.
    
## Attributions
Thanks to [Sebastian Bergmann](https://gist.github.com/sebastianbergmann) for his gist [merge-phpunit-xml.php](https://gist.github.com/sebastianbergmann/4405658), which was the base and inspired me to develop this library.

## Thoughts
Built with love. Hope you'll enjoy.. :-)
