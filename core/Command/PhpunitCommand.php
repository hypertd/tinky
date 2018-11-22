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

use Psy\Output\ShellOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Tinky\Command\PassthroughCommand;

/**
 * Interact with the current code buffer.
 *
 * Shows and clears the buffer for the current multi-line expression.
 */
class PhpunitCommand extends PassthroughCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $definition = [
        //'atleast-version='          => null,
        //'prepend='                  => null,
        //'bootstrap='                => null,
        //'cache-result'              => null,
        //'cache-result-file='        => null,
        //'check-version'             => null,
        //'colors=='                  => null,
        //'columns='                  => null,
        //'configuration='            => null,
        //'coverage-clover='          => null,
        //'coverage-crap4j='          => null,
        //'coverage-html='            => null,
        //'coverage-php='             => null,
        //'coverage-text=='           => null,
        //'coverage-xml='             => null,
        new InputOption('debug', '', InputOption::VALUE_OPTIONAL, 'Enable Debug', false),
        //'disallow-test-output'      => null,
        //'disallow-resource-usage'   => null,
        //'disallow-todo-tests'       => null,
        //'default-time-limit='       => null,
        //'enforce-time-limit'        => null,
        //'exclude-group='            => null,
        //'filter='                   => null,
        //'generate-configuration'    => null,
        //'globals-backup'            => null,
        //'group='                    => null,
        //'help'                      => null,
        //'resolve-dependencies'      => null,
        //'ignore-dependencies'       => null,
        //'include-path='             => null,
        //'list-groups'               => null,
        //'list-suites'               => null,
        //'list-tests'                => null,
        //'list-tests-xml='           => null,
        //'loader='                   => null,
        //'log-junit='                => null,
        //'log-teamcity='             => null,
        //'no-configuration'          => null,
        //'no-coverage'               => null,
        //'no-logging'                => null,
        //'no-extensions'             => null,
        //'order-by='                 => null,
        //'printer='                  => null,
        //'process-isolation'         => null,
        new InputOption('repeat', '', InputOption::VALUE_OPTIONAL, 'Should repeat', false),
        //'dont-report-useless-tests' => null,
        //'random-order'              => null,
        //'random-order-seed='        => null,
        //'reverse-order'             => null,
        //'reverse-list'              => null,
        //'static-backup'             => null,
        //'stderr'                    => null,
        //'stop-on-defect'            => null,
        //'stop-on-error'             => null,
        //'stop-on-failure'           => null,
        //'stop-on-warning'           => null,
        //'stop-on-incomplete'        => null,
        //'stop-on-risky'             => null,
        //'stop-on-skipped'           => null,
        //'fail-on-warning'           => null,
        //'fail-on-risky'             => null,
        //'strict-coverage'           => null,
        //'disable-coverage-ignore'   => null,
        //'strict-global-state'       => null,
        //'teamcity'                  => null,
        new InputOption('testdox', '', InputOption::VALUE_OPTIONAL, 'Format testdox', false),
        //'testdox-group='            => null,
        ///'testdox-exclude-group='    => null,
        //'testdox-html='             => null,
        //'testdox-text='             => null,
        //'testdox-xml='              => null,
        //'test-suffix='              => null,
        //'testsuite='                => null,
        //'verbose'                   => null,
        ///'version'                   => null,
        //'whitelist='                => null,
        //'dump-xdebug-filter='       => null,
        new InputArgument('files', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Files or directory'),
        ];

        $this
            ->setName('phpunit')
            ->setDefinition($definition)
            ->setAliases(array('pu'))
            ->setDescription('Executes PHPUnit with given options')
            ->setHelp(
                <<<'HELP'
PHPunit passthrough command
HELP
            );

        $this->ignoreValidationErrors();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input = null, OutputInterface $output = null)
    {
        $runner = new \PHPUnit\TextUI\TestRunner;
        $argument = $input->getArgument('files');
        $options =  $input->getOptions();

        $suite = $argument;
        if(gettype($argument) == 'array'){
            $suite =  implode(' ', $argument);
        }

        foreach ($options as $key => $value) {
            $options['--'.$key] = $value;
            unset($options[$key]);
        }

        try{
            $_SERVER['REQUEST_TIME_FLOAT'] = \microtime(true);
            chdir('..'); //switch to the application level
            $result = $runner->doRun($runner->getTest($suite), $options, false);
        }
        catch (\Exception $e){
            echo $e->getMessage()."\n";
        }
        //$command->run(array('phpunit'), false);
    }
}