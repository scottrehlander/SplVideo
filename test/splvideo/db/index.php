<?php 
	session_start(); 
	require("db/ScottDB.php");
	$con = getConnection();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>SPL Videos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="template/1.css" rel="stylesheet" type="text/css" />
</head>
<body> 

	<div id="logo">
		<img src="logo.png"/>
		<br/><br/><br/><br/>
		<form>
			<input type="text" name="search" size="65" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" />
		</form>
	</div>
	
	<?php
		
		$featuredVideos = getRandomFeaturedVideos($con, 3);
		print_r($featuredVideos);
		die();
	?>
	

</body>
</html>