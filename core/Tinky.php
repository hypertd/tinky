<?php
class Tinky{
    public $root;
    
    private $defaultIncludes = [];
    
    public function __construct(){
        $root = $this->root = __DIR__ . '/..';
        
        $this->defaultIncludes = [
           $root."/Whiteboard.php"
        ];
            
        //required config setup
        $this->bootConfig = (include $root.'/Config.php');
        
        //framework if set
        if(isset($this->bootConfig['bootstrapFile']) && file_exists(__DIR__.'/core/bootstraps/'.$this->bootConfig['bootstrapFile'])){
            $this->defaultIncludes[] = $root.'/core/bootstraps/'.$bootConfig['bootstrapFile'];
        }
    }
    
    public function shell(){
        $config = new \Psy\Configuration();
        $config->setDefaultIncludes($this->defaultIncludes);
        $shell = new \Psy\Shell($config);
        $shell->run();
    }
}