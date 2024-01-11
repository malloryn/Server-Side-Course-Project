<?php
/**
 * This file defines PDO database package. This file is included in any files that needs database connection
 * http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers
 * http://php.net/manual/en/pdostatement.fetch.php
  */

/*** mysql hostname ***/
$hostname = 'localhost';

/*** mysql username ***/
$username = 'mjnewber';

/*** mysql password ***/
$password = 'M8400new';

try {
    	$con = new PDO("mysql:host=$hostname;port=3306;dbname=mjnewber_db", $username, $password);
    	/*** echo a message saying we have connected ***/
    	// echo 'Connected to database';
    }
catch(PDOException $e)
    {
    	echo $e->getMessage();
	//echo "Something is wrong. Please try again later or contact the system administrator. ";

    }
?>