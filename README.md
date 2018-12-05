
**Tinky project has been inspired and built on Psysh** (http://psysh.org/).
**psysh is a runtime developer console, interactive debugger and  REPL  for PHP.**

Tinky extends psysh with framework agnostic bootstrapping and more. Tinky is a tool and is meant to be dropped into the root of your project (possibly globally installed in the future), i suggest adding it to your git global ignore.

**Quick breakdown:**

**/bootstraps** - contains framework bootstraps for hooking into applications
**/core** - core code for tinky's setup
**/workbench** - can be used for testing of an existing application or development of a fresh one. Freedom.
**/workbench/src** - source for your tool or application development
**/workbench/tests** - relative test running, use this like `tinky --t="Test.php"` to test run code. 

You can run composer install in the workbench directory for some default tools too work with.

**For debugging use:**

`\Tinky\Shell::debug(get_defined_vars());`

to code-break until I've found a better way extend the shell.

**Standard commands from psysh**

show
list
wtf
history
doc
dump
ls
trace

**Extra Commands**

ostack (wip)
phpunit (wip)