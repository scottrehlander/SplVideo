<?php 
	session_start(); 
	require("db/ScottDB.php");
	
	$dbAccess = new DbAccess();
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
			$dbAccess->addVideo($_POST);
		}
	
	?>
	<div id="logo">
		<img src="logo.png"/>
		<br/><br/><br/><br/>
		<form action="AddVideo.php" method="post">
			<table>
				<tr>
					<td>
						Video Name:
					</td>
					<td>
						<input type="text" name="videoName" size="65" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" />
					</td>
				</tr>
				<tr>
					<td>
						URL: 
					</td>
					<td>
						<input type="text" name="videoUrl" size="65" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" />
					</td>
				</tr>
				<tr>
					<td>
						Description: 
					</td>
					<td>
						<input type="text" name="videoDescription" size="65" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" />
					</td>
				</tr>
				<tr>
					<td>
						Keywords: 
					</td>
					<td>
						<input type="text" name="keywords" size="65" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" />
					</td>
				</tr> 
				<tr>
					<td>
						Is Featured: 
					</td>
					<td>
						<input type="text" name="isFeatured" size="65" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" />
					</td>
				</tr>
			
				<tr><td><input type="submit" name="submit" value="Add Video" /></td></tr>
			</table>
		</form>
	</div>
	

</body>
</html>