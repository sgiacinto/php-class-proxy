		var req = {};				  // create the empry request object
		var params = [];			  // create the empty parameters array
		params.push("param1");        // add a parameter
		params.push("param2");        // add another parameter ... etc.
		
		req.classname="MyClassName";      // the class name
		req.method = "MyMethodName";  // the function in the class (method)
		req.params = params;          // array of parameters in the order the function expects them
		
			var request = $.ajax({
			  url: "proxy.php",           // the proxy file
			  type: "POST",
			  data: JSON.stringify(req),  // convert the request to JSON
			  processData: false,
			  dataType: "json"
			});