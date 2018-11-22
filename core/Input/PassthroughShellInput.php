<?php
namespace Tinky\Input;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\StringInput;

class PassthroughShellInput extends \Psy\Input\ShellInput{

    /**
     * Unlike the parent implementation's tokens, this contains an array of
     * token/rest pairs, so that code arguments can be handled while parsing.
     */
    private $tokenPairs;
    private $parsed;

    /**
     * Constructor.
     *
     * @param string $input An array of parameters from the CLI (in the argv format)
     */
    public function __construct($input)
    {
        parent::__construct($input);
        $this->tokenPairs = $this->tokenize($input);
    }

     /**
     * Parses an argument, with bonus handling for code arguments.
     *
     * @param string $token The current token
     * @param string $rest  The remaining unparsed input, including the current token
     *
     * @throws \RuntimeException When too many arguments are given
     */
    private function parseShellArgument($token, $rest)
    {

        $c = count($this->arguments);

        if(!$c && $token != ''){

        }

        // if input is expecting another argument, add it
        if ($this->definition->hasArgument($c)) {
            $arg = $this->definition->getArgument($c);

            if ($arg instanceof CodeArgument) {
                // When we find a code argument, we're done parsing. Add the
                // remaining input to the current argument and call it a day.
                $this->parsed = array();
                $this->arguments[$arg->getName()] = $rest;
            } else {
                $this->arguments[$arg->getName()] = $arg->isArray() ? array($token) : $token;
            }

            return;
        }

        // if last argument isArray(), append token to last argument
        if ($this->definition->hasArgument($c - 1) && $this->definition->getArgument($c - 1)->isArray()) {
            $arg = $this->definition->getArgument($c - 1);
            $this->arguments[$arg->getName()][] = $token;

            return;
        }

        return;
    }


      /**
     * Same as parent, but with some bonus handling for code arguments.
     */
    protected function parse()
    {
        $parseOptions = true;
        $this->parsed = $this->tokenPairs;
        while (null !== $tokenPair = array_shift($this->parsed)) {
            // token is what you'd expect. rest is the remainder of the input
            // string, including token, and will be used if this is a code arg.
            list($token, $rest) = $tokenPair;

            if ($parseOptions && '' === $token) {
                $this->parseShellArgument($token, $rest);
            } elseif ($parseOptions && '--' === $token) {
                $parseOptions = false;
            } elseif ($parseOptions && 0 === strpos($token, '--')) {
                $this->parseLongOption($token);
            } elseif ($parseOptions && '-' === $token[0] && '-' !== $token) {
                $this->parseShortOption($token);
            } else {
                $this->parseShellArgument($token, $rest);
            }
        }
    }

    /**
     * Tokenizes a string.
     *
     * Same as parent since its private
     */
    private function tokenize($input)
    {
        $tokens = array();
        $length = strlen($input);
        $cursor = 0;
        while ($cursor < $length) {
            if (preg_match('/\s+/A', $input, $match, null, $cursor)) {
            } elseif (preg_match('/([^="\'\s]+?)(=?)(' . StringInput::REGEX_QUOTED_STRING . '+)/A', $input, $match, null, $cursor)) {
                $tokens[] = array(
                    $match[1] . $match[2] . stripcslashes(str_replace(array('"\'', '\'"', '\'\'', '""'), '', substr($match[3], 1, strlen($match[3]) - 2))),
                    substr($input, $cursor),
                );
            } elseif (preg_match('/' . StringInput::REGEX_QUOTED_STRING . '/A', $input, $match, null, $cursor)) {
                $tokens[] = array(
                    stripcslashes(substr($match[0], 1, strlen($match[0]) - 2)),
                    substr($input, $cursor),
                );
            } elseif (preg_match('/' . StringInput::REGEX_STRING . '/A', $input, $match, null, $cursor)) {
                $tokens[] = array(
                    stripcslashes($match[1]),
                    substr($input, $cursor),
                );
            } else {
                // should never happen
                throw new \InvalidArgumentException(sprintf('Unable to parse input near "... %s ..."', substr($input, $cursor, 10)));
            }

            $cursor += strlen($match[0]);
        }

        return $tokens;
    }



     /**
     * Parses a short option.
     *
     * @param string $token The current token
     */
    private function parseShortOption($token)
    {
        $name = substr($token, 1);

        if (strlen($name) > 1) {
            if ($this->definition->hasShortcut($name[0]) && $this->definition->getOptionForShortcut($name[0])->acceptValue()) {
                // an option with a value (with no space)
                $this->addShortOption($name[0], substr($name, 1));
            } else {
                $this->parseShortOptionSet($name);
            }
        } else {
            $this->addShortOption($name, null);
        }
    }

    /**
     * Parses a short option set.
     *
     * @param string $name The current token
     *
     * @throws \RuntimeException When option given doesn't exist
     */
    private function parseShortOptionSet($name)
    {
        //
    }

    /**
     * Parses a long option.
     *
     * @param string $token The current token
     */
    private function parseLongOption($token)
    {
        $name = substr($token, 2);

        if (false !== $pos = strpos($name, '=')) {
            if (0 === strlen($value = substr($name, $pos + 1))) {
                // if no value after "=" then substr() returns "" since php7 only, false before
                // see http://php.net/manual/fr/migration70.incompatible.php#119151
                if (PHP_VERSION_ID < 70000 && false === $value) {
                    $value = '';
                }
                array_unshift($this->parsed, array($value, null));
            }
            $this->addLongOption(substr($name, 0, $pos), $value);
        } else {
            $this->addLongOption($name, null);
        }
    }

    /**
     * Adds a short option value.
     *
     * @param string $shortcut The short option key
     * @param mixed  $value    The value for the option
     *
     * @throws \RuntimeException When option given doesn't exist
     */
    private function addShortOption($shortcut, $value)
    {
    }

    /**
     * Adds a long option value.
     *
     * @param string $name  The long option key
     * @param mixed  $value The value for the option
     *
     * @throws \RuntimeException When option given doesn't exist
     */
    private function addLongOption($name, $value)
    {
    }
}