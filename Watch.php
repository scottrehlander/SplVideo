<?php 
	session_start(); 
	require("db/ScottDB.php");
	require("db/Utilities.php");
	
	$dbAccess = new DbAccess();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>CAR AUDIO SPL VIDEOS | Extreme Bass @ SplVideos.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="english" />
<meta name="description" content="The best place to browse extremely high decible level car audio subwoofer videos.  View dbDrag hair tricks, USACi shattered windshields, IASCA runs, massive car audio competition systems, etc..  Check out new car audio equipment including mid-level speakers, subwoofers, amplifiers, capacitors, head units, in-dash navigation, and more!" />
<link href="template/1.css" rel="stylesheet" type="text/css" />
<link href="template/roundedbox/dialog.css" rel="stylesheet" type="text/css" media="screen" />

<?php
	includeVideoLightBoxHead();
?>

</head>
<body> 

	<?php
		$videos = array();
		
		$searchString = "Search Videos";		
		
		// Get the keywords for this video
		if(isset($_GET['video']))
		{
			$videoId = $_GET['video'];
			$thisVideo = $dbAccess->getVideoById($videoId);
			
			try
			{
				$dbAccess->incrementTimesWatched($videoId);
			}
			catch (Exception $ex) { die($ex); }
			
			$keywords = $dbAccess->getKeywordsFromVideoId($videoId);
			$keywordString = "";
			foreach($keywords as $keyword)
			{
				$keywordString .= " " . $keyword['keyword'];
			}
			
			$videos = $dbAccess->getVideosFromKeywords($keywordString, 'random', 5);
		}
		
		$sortValue = "Date Added";
		if(isset($_GET['sort']))
		{
			if($_GET['sort'] == "nV")
				$sortValue = "Number of Views";
		}
				
		// If we posted back a rating, update
		$rated = false;
		if(isset($_GET['rating']) && isset($_GET['video']))
		{
			$rated = $dbAccess->didIpRate($_GET['video']);
			if($rated == false)
			{
				if($_GET['rating'] == 1 ||
					$_GET['rating'] == -1)
				{
					$dbAccess->insertRating($_GET['video'], $_GET['rating']);
				}
			}
		}
		$rated = $dbAccess->didIpRate($_GET['video']);
	?>

  <div id="content">
	
	<img src="smallLogo.png" style="top:0px;left:0px" />
  
		<div id="mainSearchBar">
			<form action="index.php">
			<p>
				<table>
					<tr>
						<td>
							<input type="text" name="search" value="<?php echo($searchString); ?>" size="58" style="border-width:1px;border-color:#000000;height:24px;font-size:18px;vertical-align:top" 
								onfocus="if(this.value==this.defaultValue) this.value='';" />
						</td>
						<td>
							<input type="Submit" value="Search" />
						</td>
					</tr>
					<?php
						if(isset($_GET['search']))
						{
							echo("<tr><td>");
								echo("<p style=\"font-size:10px\">Results found: " . count($videos) . "</p>");
							echo("</tr></td>");
						}
					?>
				</table>
			<p>
			</form>
		</div>
  
	  <div id="mainNavigation">
		<ul style="list-style:none;">
		
			<li><a href="index.php">Home</a></li>
		<?php
			$categories = $dbAccess->getCategories($con);
		
			foreach($categories as $category)
			{
				echo("<li>");
					echo("<a href=\"index.php?search=" . $category['categoryKeywords'] . "\">" . $category['categoryName'] . "</a>");
				echo("</li>");
			}
		?>
		<li>
			<a href="index.php?random=1">Random Videos</a>
		</li>

		</ul>
	  </div>
	  
  
		<!-- Start Video section -->
		<div id="mainVideoDisplay">
			<div id="videogallery">				
				<?php
					// Show the main video
					showEmbeddedYtVideo($thisVideo['videoUrl']);
				
					//Show rating
					if($rated == false)
					{
						echo("<div id=\"ratingDisplay\">");
							echo("<a href=\"Watch.php?video=" . $thisVideo['videoId'] . "&rating=1\"><img src=\"images/thumbs_up.png\"></a>");
							echo("<a href=\"Watch.php?video=" . $thisVideo['videoId'] . "&rating=-1\"><img src=\"images/thumbs_down.png\"></a>");
						echo("</div>");
						echo("<br /><br />");
					
					}
					
					echo("<br />");
					echo("<h2>Link this video on your myspace or website!</h1>");
					echo("<table width=\"640px\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\"><tr><td>");
					echo("&lt;table border=\"1\" cellpadding=\"1\" cellspacing=\"1\"&gt;&lt;tr&gt;&lt;td&gt; &lt;a href=\"http://splvideos.com/Watch.php?video=" . $_GET['video'] . "\"&gt;" . getThumbnailString($thisVideo['videoUrl']) . 
						"&lt;/a&gt;&lt;/td&gt;&lt;td width=\"300px\"&gt;" . "&lt;a href=\"http://splvideos.com/Watch.php?video=" . $_GET['video'] . "\"&gt;SPLVideos.com: " . 
						$thisVideo["videoDescription"] . "&lt;/a&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;");
					echo("</td></tr></table>");
					echo("<br /><br />");
					echo("<h3>Related Car Audio Videos</h3>");
					// Show the rest
					showVideos($videos, $dbAccess);
				?>
				
			</div>
		</div>
		
		<div id="ads">
			<script type="text/javascript"><!--
				google_ad_client = "pub-2204550774012692";
				/* 120x600, created 8/4/10 */
				google_ad_slot = "1721394908";
				google_ad_width = 120;
				google_ad_height = 600;
				//-->
			</script>
			<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
			
			<!-- analytics -->
			<?php addAnalytics() ?>
		</div>
		
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
  </div>
</div>
</body>
</html>