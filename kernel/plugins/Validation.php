<?php
namespace kernel\plugins;

class ValidationCall{
	
	public function __call($class=null, $args=[]){
		if(@require_once dirname(__DIR__,2)."/validation/$class.php"){
			$class = "\Rakit\Validation\Rules\\$class";
			return new $class();
		}
	}
}