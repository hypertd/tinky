<?php
namespace Tinky\Tools;

use Ubench;

class Util {
	public $logger, 
		$showlogs = false, 
		$entityManager = null,
		$bench = null;

	public function __construct($entityManager){
		$this->entityManager = $entityManager;
	}

	public function showlogs($value = true){
		$this->showlogs = $value;
	}

	static function dd($obj){
		\Doctrine\Common\Util\Debug::dump($obj);
	}

	public function startLogger(){
		$this->logger = new \Doctrine\DBAL\Logging\DebugStack();
		$this->entityManager->getConnection()
	    ->getConfiguration()
	    ->setSQLLogger($this->logger);

	    return $this->logger;
	}

	public function printLogs(){
		$index = 0;
		foreach ($this->logger->queries as $query) {
			echo $index . ' | '. $query['sql'] . ' | ' . json_encode($query['params']) . "\n\n";
			$index++;
		}
	}

	public function compare($obj1, $obj2){
		$engine = new \Pitpit\Component\Diff\DiffEngine();
		$diff = $engine->compare($obj1, $obj2);

		$this->trace($diff);
	}

	public function startTest(){
		$this->startLogger($this->entityManager);

		$this->bench = new Ubench;
		$this->bench->start();
	}

	public function endTest(){
		//end bench
		$this->bench->end();

		if($this->showlogs){
			$this->printLogs();
		}	

		echo count($this->logger->queries) . " queries \n";	
		echo $this->bench->getTime() . "\n";
	}

	private function trace($diff, $tab = ''){
	    foreach ($diff as $element) {
	        $c = $element->isTypeChanged()?'T':($element->isModified()?'M':($element->isCreated()?'+':($element->isDeleted()?'-':'=')));

	        // print_r(sprintf("%s* %s [%s -> %s] (%s)\n", $tab, $element->getIdentifier(), is_object($element->getOld())?get_class($element->getOld()):gettype($element->getOld()), is_object($element->getNew())?get_class($element->getNew()):gettype($element->getNew()), $c));
	        print_r(sprintf("%s* %s [%s -> %s] (%s)\n", $tab, $element->getIdentifier(), gettype($element->getOld()), gettype($element->getNew()), $c));


	        if ($diff->isModified()) {
	            $this->trace($element, $tab . '  ');
	        }
	    }
	}


	public function checkDiffMulti($array1, $array2, $break = null){
	    $result = array();
	    foreach($array1 as $key => $val) {
	       if(isset($array2[$key])){
	           if(is_array($val) && $array2[$key]){
	           	   if($key == $break) eval(\Psy\sh());
	               $result[$key] = $this->checkDiffMulti($val, $array2[$key]);
	           }
	       } else {
	           $result[$key] = $val;
	       }
	    }

	    return $result;
	}
}