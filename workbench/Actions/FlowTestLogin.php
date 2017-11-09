<?php

namespace Tinky\Workbench\Actions;


use Tinky\Workbench\Interfaces\Runnable;


class FlowTestLogin implements Runnable {
    
    public function testSignUp()
    {
        return "success";
    }
    
    public function run()
    {
        return "ran";
    }
    
    protected function login()
    {
        
    }
}

?>