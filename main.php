<?php 
	session_start(); 
	require("db/ScottDB.php");
	require("db/Utilities.php");
	
	$dbAccess = new DbAccess();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<title>CAR AUDIO SPL VIDEOS | Extreme Bass @ SplVideo.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="english" />
<meta name="description" content="The best place to browse extremely high decible level subwoofers.  View hair tricks, shattered windshields, dbdrag runs, etc.." />
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
		
		if(isset($_GET['search']) && trim($_GET['search']) != '')
		{
			$videos = $dbAccess->getVideosFromKeywords($_GET['search']);
			$searchString = $_GET['search'];
			$dbAccess->logSearchTerm($_GET['search']);
		}
		else if(isset($_GET['random']))
		{
			$videos = $dbAccess->getRandomVideos(10);
		}
		else
		{
			// There is no search
			// Get featured or random or by date or something
			$videos = $dbAccess->getVideosByDate(0, 6);
		}
	?>

  <div id="content">
	
	<img src="smallLogo.png" style="top:0px;left:0px" />
  
		<div id="mainSearchBar">
			<form>
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
		
		<?php
			$categories = $dbAccess->getCategories($con);
			
			foreach($categories as $category)
			{
				echo("<li>");
					echo("<a href=\"main.php?search=" . $category['categoryKeywords'] . "\">" . $category['categoryName'] . "</a>");
				echo("</li>");
			}
		?>
		<li>
			<a href="main.php?random=1">Random Videos</a>
		</li>

		</ul>
	  </div>
	  
  
		<!-- Start VideoLightBox.com BODY section -->
		<div id="mainVideoDisplay">
			<div id="videogallery">
				<?php
					foreach($videos as $video)
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
			<!-- End VideoLightBox.com BODY section -->
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