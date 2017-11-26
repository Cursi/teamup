<?php
	$conn = mysqli_connect("teamupdb.mysql.database.azure.com","razvan@teamupdb","Cursi@Cursi123", "teamup");
	if (mysqli_connect_errno()) {
		die("Database error: " . mysqli_connect_error());
	}
	session_start();
?>
