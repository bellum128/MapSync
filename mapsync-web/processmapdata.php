<?php 
	$key = "default";
	if(strpos(trim(urldecode(file_get_contents("php://input"))), $key) !== false)
	{
		file_put_contents("mapdata.txt", trim(urldecode(file_get_contents("php://input"))));  // Get request from Gmod. Save to mapdata file.	
	}	
?>
