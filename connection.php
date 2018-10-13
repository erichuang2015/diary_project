<?php
//connect to database start
	
	!$link=mysqli_connect("shareddb1e.hosting.stackcp.net","diary-data-3637d83a","users1-3637d99f","diary-data-3637d83a");
	
	if (mysqli_connect_error()) {
		
		die( "Error: Unable to connect to Database");
		
	}

	?>