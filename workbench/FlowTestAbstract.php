<?php
namespace Tinky\Workbench;

use Tinky\WorkbenchInterfaces\Runnable;

class FlowTestAbstract {
    
    protected $providers = [];
        
        /**
         * get  selected provider
         * 
         */
        public function get($id)
        {
            $name = $this->providers[$id];
            $class = new $name;
            return $class;
        }
        
        /**
         * run single selected provider
         * 
         */
        public function run($id)
        {
            $class = $this->get($id);
            if ($class instanceof Runnable) {
                return $class->run();
            } else {
                return "ERROR ".get_class($class)." needs to implemet ". Runnable::class;
            }
        }
        
        /**
         * run multiple selected providers
         * 
         */
        public function runSelected($selected = []) 
        {
            foreach($selected as $id) {
                echo $this->run($id);
                echo "\n";
            }
        }
}

?>