<?php 
	session_start(); 
	require("db/ScottDB.php");
	require("db/Utilities.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>SPL Videos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="google-site-verification" content="LQEO37vwnAehyiTVY6zn4EANumDN0t6wrIk9MEV6q6s" />
	<link href="template/1.css" rel="stylesheet" type="text/css" />
	<link href="template/roundedbox/dialog.css" rel="stylesheet" type="text/css" media="screen" />
	
	<?php
		includeVideoLightBoxHead();
	?>
	
</head>
<body> 

	<?php addAnalytics(); ?>

	<div id="logo">
		<img src="logo.png"/>
		<br/><br/><br/><br/>
		<form>
			<input type="text" name="search" value="Search Videos" size="65" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" 
				onfocus="if(this.value==this.defaultValue) this.value='';" />
		</form>
		<br/>
		
		<div id="navigation">
			<a href="main.php">Browse All Videos</a>
		</div>
		
		<br/>	<br/>	

		<div id="videogallery">
			<?php
				$featuredVideos = getRandomFeaturedVideos(3);
				foreach($featuredVideos as $video)
				{
					echo("<div id=\"videoRow\">");
						echo("<table><tr>");
						echo("<td>");
							showLightBoxVideo($video['videoName'], $video['videoUrl'], $video['videoThumbnail']);
						echo("</td><td>");
							echo("<p style=\"text-align: left;\">" . $video['videoDescription'] . "</p>");
						echo("</td><td>");
							// Grab the date
							$date = explode(' ', $video['dateAdded']);
							$date = explode('-', $date[0]);
							$date = $date[1] . '/' . $date[2] . '/' . $date[0];
							echo("<div style=\"vertical-align: top;font-size:11px;height: 90px;\">" . $date . "</div>");
						echo("</td></tr></table>");
					echo("</div>");
				}
			?>
		</div>		
	</div>
		
	

</body>
</html>