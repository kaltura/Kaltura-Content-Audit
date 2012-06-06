<?php
/* 
 * temporary logging class for mediaspace
 * use only for debugging - creates huge files.
 * Search for "logger" in code to find places where additional code needs to be uncommented
 */

class logerClass implements IKalturaLogger
{

    private $logHandler;

    function __construct($logFilePath)
    {
		$this->logHandler = fopen($logFilePath, "a+");
    }
    
    
    function log($msg)
    {
		// fwrite($this->logHandler, $msg.PHP_EOL);
    }

    function __destruct()
    {
		fclose($this->logHandler);
    }

}

?>
