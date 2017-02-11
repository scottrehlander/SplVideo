<?php 
	session_start(); 
	require("db/ScottDB.php");
	require("db/Utilities.php");
	$con = getConnection();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>SPL Videos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="template/1.css" rel="stylesheet" type="text/css" />
	<in
</head>
<body> 

	<?php
	
		if($_POST['submit'])
		{
			parseVideoLightboxOutput($con, $_POST['input']);
		}
	
	?>
	<div id="logo">
		<img src="logo.png"/>
		<br/><br/><br/><br/>
		<form action="AddVideo2.php" method="post">
			<table>
				<tr>
					<td>
						Input:
					</td>
					<td>
						<textarea name="input">Thank you for visiting the site. We would appreciate it if you would leave some comments in this box to help us improve.</textarea>
					
						
					</td>
				</tr>
				<tr><td><input type="submit" name="submit" value="Add Video" /></td></tr>
			</table>
		</form>
	</div>
	

</body>
</html>