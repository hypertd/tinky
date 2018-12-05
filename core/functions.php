<?php

namespace Tinky;

if (!function_exists('Tinky\debug')) {
    /**
     * Invoke a Psy Shell from the current context.
     *
     * For example:
     *
     *     foreach ($items as $item) {
     *         \Psy\debug(get_defined_vars());
     *     }
     *
     * If you would like your shell interaction to affect the state of the
     * current context, you can extract() the values returned from this call:
     *
     *     foreach ($items as $item) {
     *         extract(\Psy\debug(get_defined_vars()));
     *         var_dump($item); // will be whatever you set $item to in Psy Shell
     *     }
     *
     * Optionally, supply an object as the `$boundObject` parameter. This
     * determines the value `$this` will have in the shell, and sets up class
     * scope so that private and protected members are accessible:
     *
     *     class Foo {
     *         function bar() {
     *             \Psy\debug(get_defined_vars(), $this);
     *         }
     *     }
     *
     * This only really works in PHP 5.4+ and HHVM 3.5+, so upgrade already.
     *
     * @param array  $vars        Scope variables from the calling context (default: array())
     * @param object $boundObject Bound object ($this) value for the shell
     *
     * @return array Scope variables from the debugger session
     */
    function debug(array $vars = array(), $boundObject = null)
    {
        echo PHP_EOL;

        $sh = new Shell();
        $sh->setScopeVariables($vars);

        // Show a couple of lines of call context for the debug session.
        //
        // @todo come up with a better way of doing this which doesn't involve injecting input :-P
        if ($sh->has('whereami')) {
            $sh->addInput('whereami -n2', true);
        }

        if ($boundObject !== null) {
            $sh->setBoundObject($boundObject);
        }

        $sh->run();

        return $sh->getScopeVariables(false);
    }
}