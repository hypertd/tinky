<?php
namespace Tinky;

class Tinky{
    public $root;
    
    private $defaultIncludes = [];
    
    public function __construct(){
        $root = $this->root = __DIR__ . '/..';
        
        //required config setup
        $this->bootConfig = (include $this->root.'/Config.php');

        //core commands
        $commands = [
            new Command\PhpunitCommand
        ];

        $this->addCommands($commands); 

        //framework if set
        if(isset($this->bootConfig['bootstrapFile']) && file_exists($this->root.'/bootstraps/'.$this->bootConfig['bootstrapFile'])){
            $this->addDefaultInclude($this->root.'/bootstraps/'.$this->bootConfig['bootstrapFile']);

            //mitigate autoload order issues
            unset($this->bootConfig['bootstrapFile']);
        }


        //load testing tools
        if(file_exists($this->root.'/workbench/vendor/autoload.php')){
            $this->addDefaultInclude($root.'/workbench/vendor/autoload.php');
        }
        else{
            echo "Tools missing!\n\n";
        }

        //optional test param
        $options = getopt("", ["test::", "t::", "empty::", "e::"]);

        if(!empty($options)){
            if(isset($options["test"])) $this->addDefaultInclude($root."/workbench/tests/".$options["test"]);
            if(isset($options["t"])) $this->addDefaultInclude($root."/workbench/tests/".$options["t"]);

            if(isset($options["e"]));
            if(isset($options["empty"]));
        }
        else{
            $this->addDefaultInclude($root."/workbench/tests/Whiteboard.php");
        }
    }

    public function addCommands(array $commands){
        $this->bootConfig = array_merge($this->bootConfig, array('commands' =>$commands));
    }

    public function addDefaultInclude($path){
        $defaults = $this->defaultIncludes;
        array_push($defaults, $path);

        $this->defaultIncludes = $defaults;
    }
    
    public function shell(){
        $config = new \Psy\Configuration($this->bootConfig);
        $config->setDefaultIncludes($this->defaultIncludes);

        $shell = new \Tinky\Shell($config);
        $shell->run();
    }
}