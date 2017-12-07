<?php
class Tinky{
    public $root;
    
    private $defaultIncludes = [];
    
    public function __construct(){
        $root = $this->root = __DIR__ . '/..';
        
        //required config setup
        $this->bootConfig = (include $this->root.'/Config.php');
        
        //framework if set
        if(isset($this->bootConfig['bootstrapFile']) && file_exists($this->root.'/core/bootstraps/'.$this->bootConfig['bootstrapFile'])){
            $this->defaultIncludes[] = $this->root.'/core/bootstraps/'.$this->bootConfig['bootstrapFile'];
        }
        
        $this->defaultIncludes[] = $root."/Whiteboard.php";
    }
    
    public function shell(){
        $config = new \Psy\Configuration();
        $config->setDefaultIncludes($this->defaultIncludes);
        $shell = new \Psy\Shell($config);
        $shell->run();
    }
}