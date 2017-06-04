# php-class-proxy
PHP script for remote execution of class methods 
# Purpose
This PHP script can be called by Javascript and will load a PHP class and execute a method with parameters and return any result in the desired format. The purpose is to make calling class methods from an AJAX client transparent. 
# How to use:
* Make sure your class is in a file of the same name, 1 class per file, and included in your PHP include path
* add any classes you want to be able to call to the array $allowedSecureClasses 
* ensure the order of the parameters passed is the same order as in the method

