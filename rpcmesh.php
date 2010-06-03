<?php
	
	// PHP5 Implementation - uses MySQLi.
	// connect as select only user
	$db = new mysqli('localhost', 'user' ,'passwd', 'autoCompleteDB');
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERROR: Could not connect to the database.';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $db->real_escape_string($_POST['queryString']);
			if(strlen($queryString) >0) {
				$query = $db->query("SELECT value,fitness FROM autoCompleteTable WHERE value LIKE '$queryString%' order by fitness desc LIMIT 23");
				if($query) {
					// While there are results loop through them - fetching an Object 
					while ($result = $query ->fetch_object()) {
						// Format the results, im using <li> for the list, you can change it.
						// The onClick function fills the textbox with the result.
					$cheese = $result->value;
					$cheese = preg_replace('/\'/','\\\'',$cheese);
					//echo '<li onClick="fill(\''.$cheese.'\');">'.$result->value.' : '.$result->fitness.'</li>';
					echo '<li onClick="fill(\''.$cheese.'\');">'.$result->value.'</li>';
	         		}
				} else {
					echo 'ERROR: There was a problem with the query. '. $query;
				}
			} else {
				// Dont do anything.
			} // There is a queryString.
		} else {
			echo 'There should be no direct access to this script!';
		}
	}
?>
