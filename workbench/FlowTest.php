<?php
namespace Tinky\Workbench;

use Tinky\Workbench\Actions\FlowTestSignup;
use Tinky\Workbench\Actions\FlowTestLogin;
use Tinky\Workbench\Interfaces\Runnable;

class FlowTest extends FlowTestAbstract {
    
    protected $providers = [
        "signup" => FlowTestSignup::class,
        "login" => FlowTestLogin::class,
        ];
}

?>