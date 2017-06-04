<?php

$t = new StdClass;

	$allowedSecureClasses =    array("class1", "class2");  // classes allowed  
	
	
	if(isset($_GET["p"])) {						  // if it's a GET
		$raw =  $_GET["p"]; 
	}
	else {
		$raw = file_get_contents("php://input");  // get the complete HTTP POST content
	}	
	
	if($raw) {
	
		$data = json_decode($raw);          // decode the json to an object
		if(is_object($data)) {              // check it is a real object

	// the decoded object should contain:
			
			$class =   $data->classname;    // class:       String - the name of the class (filename must = classname) and file must be in the include path
			$method =  $data->method;       // method:      String - the name of the function within the class (method)
			@$params = $data->params;		// params:      Array  - optional - an array of parameter values in the order the funtion expects them
			@$type =   $data->type;   		// type:        String - optional - data format of the return value, default: json || values can be: json, text, html
			
	// set type to json if not specified
			if(!$type) {
				$type = "json";
			}

	// set params to empty array if not specified
			if(!$params) {
				$params = array();
			}
			
	// check that the specified class is in the allowed classes array, either anonymous or secure
			
			if(!in_array($class,$allowedSecureClasses)) {
			
				header("HTTP/1.0 404 Not Found - Class " . $class . " not found.");
				die("404 - class not found - " . $class);
			}
            
			
	    $classFile = $class . ".php";
			
			// check that the class file exists
			if(stream_resolve_include_path($classFile)) {
			
				include $class . ".php";    // if yes include it
				
				
			} else {

				header("HTTP/1.0 404 Not Found - Class file " . $classFile . " not found.");
				die("404  - Class file " . $classFile . " not found.");
			}			
			
	// instantiate the class (class name = file name)		
				$v = new $class;	
			
	// check that the function exists within the class
			
			if(!method_exists($v, $method)) {
			
				header("HTTP/1.0 404 Not Found - Method " . $method . " not found on class " . $class . ".");
				die("404 - Method not found - " . $class . " - " . $method);
			}
			
			
	// execute the function with the provided parameters
	
	try {
      		$cl = call_user_func_array(array($v,$method), $params );

			
	} catch (Exception $e) {
		
		header("HTTP/1.0 500 - A server error occurred when processing your request");
		echo "An unknown error occurred that we cannot recover from, sorry about that";
		die("500 - error - could not invoke method - " . $class . " - " . $method);
	
	}
	
	
			
	// return the results with the content type based on the $type parameter
			if($type == "json") {
				header("Content-Type:application/json");
				echo json_encode($cl);
				exit();
			}
			
			if($type == "html") {
				header("Content-Type:text/html");
				echo $cl;
				exit();
			}

			if($type == "text") {
				header("Content-Type:text/plain");
				echo $cl;
				exit();
			}
		}
		else {
			die("Invalid request.");
		}		
		
	} else {

	die("Nothing posted");
}

?>