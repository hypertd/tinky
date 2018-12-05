<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2017 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tinky\Command;

use JakubOnderka\PhpConsoleHighlighter\Highlighter;
use Psy\Configuration;
use Psy\ConsoleColorFactory;
use Psy\Output\ShellOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Show the context of where you opened the debugger.
 */
class OpenStackCommand extends WhereamiCommand
{
    private $colorMode;

    /**
     * Obtains the correct stack frame in the full backtrace.
     *
     * @return array
     */
    protected function trace()
    {
        $frameOptions = [];
        foreach (array_reverse($this->backtrace) as $stackFrame) {
            if ($this->isDebugCall($stackFrame)) {
                $frameOptions[] = $stackFrame;
            }
        }

        return $frameOptions;
    }

    /**
     * Determine the file and line based on the specific backtrace.
     *
     * @return array
     */
    protected function fileInfo()
    {
        $frameOptions = $this->trace();

        foreach ($frameOptions as $key => $stackFrame){
            if (preg_match('/eval\(/', $stackFrame['file'])) {
                preg_match_all('/([^\(]+)\((\d+)/', $stackFrame['file'], $matches);
                $file = $matches[1][0];
                $line = (int) $matches[2][0];
            } else {
                $file = $stackFrame['file'];
                $line = $stackFrame['line'];
            }

            $frameOptions[$key+1] = compact('file', 'line');
        }

        unset($frameOptions[0]);

        return $frameOptions;
    }

    /**
     * @param null|string $colorMode (default: null)
     */
    public function __construct($colorMode = null)
    {
        $this->colorMode = $colorMode ?: Configuration::COLOR_MODE_AUTO;

        if (version_compare(PHP_VERSION, '5.3.6', '>=')) {
            $this->backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        } else {
            $this->backtrace = debug_backtrace();
        }

        return parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ostack')
            ->setDefinition(array(
                new InputArgument('stacknum', InputArgument::OPTIONAL, 'Stack number from command output'),
            ))
            ->setDescription('Shows you backtraced files to open in your editor.')
            ->setHelp(
                <<<'HELP'
Shows you backtraced files to open in your editor (must be available as a cli command).

e.g.
<return>> ostack </return>
<return>> ostack 1</return>
HELP
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $frameOptions = $this->fileInfo();
        $num = $input->getArgument('stacknum');
        $factory = new ConsoleColorFactory($this->colorMode);
        $colors = $factory->getConsoleColor();
        $highlighter = new Highlighter($colors);

        if($num !== 0 && $num !== NULL){
            exec(sprintf('subl %s:%s',  $frameOptions[$num]['file'], $frameOptions[$num]['line']));
        }
        else{
            $output->startPaging();
            foreach ($frameOptions as $key => $stack) {
                $output->writeln(sprintf('%s | <info>%s:%s</info>', $key, $this->replaceCwd($stack['file']), $stack['line']));
            }
            $output->stopPaging();
        }
    }


    private static function isDebugCall(array $stackFrame)
    {
        $class    = isset($stackFrame['class']) ? $stackFrame['class'] : null;
        $function = isset($stackFrame['function']) ? $stackFrame['function'] : null;
        $file = isset($stackFrame['file']) ? $stackFrame['file'] : null;

        $flags = [];

        $flags[] = ($class === null && $function === 'Tinky\debug');
        $flags[] = ($class === 'Tinky\Shell' && in_array($function, array('__construct', 'debug')));

        return in_array(true, $flags);
    }


    /**
     * Replace the given directory from the start of a filepath.
     *
     * @param string $file
     *
     * @return string
     */
    private function replaceCwd($file)
    {
        $cwd = getcwd();
        if ($cwd === false) {
            return $file;
        }

        $cwd = rtrim($cwd, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        return preg_replace('/^' . preg_quote($cwd, '/') . '/', '', $file);
    }
}
