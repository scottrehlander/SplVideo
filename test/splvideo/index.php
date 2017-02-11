<?php 
	session_start(); 
	require("db/ScottDB.php");
	require("db/Utilities.php");
	
	$VIDS_PER_PAGE = 10;
	$numOfPages = 1;
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

<title>Redirect menu</title>

<script language="JavaScript">

	function loadPage(list) {
		//location.href=list.options[list.selectedIndex].value;
	  
		var getSearchParam = gup('search');
		var url = "index.php?";
		
		if(getSearchParam != "")
			url += "search=" + getSearchParam + "&";
		if(list.options[list.selectedIndex].value == "Times Viewed")
			url+="sort=tV";
		else if(list.options[list.selectedIndex].value == "Most Liked")
			url+="sort=mL";

		location.href=url;
	}

	// function get grab the get params from the url
	function gup( name )
	{
	  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	  var regexS = "[\\?&]"+name+"=([^&#]*)";
	  var regex = new RegExp( regexS );
	  var results = regex.exec( window.location.href );
	  if( results == null )
		return "";
	  else
		return results[1];
	}

</script>

</head>
<body> 

	<?php
			
		$sortValue = "Date Added";
		if(isset($_GET['sort']))
		{
			if($_GET['sort'] == "tV")
				$sortValue = "Times Viewed";
			else if($_GET['sort'] == "mL")
				$sortValue = "Most Liked";
		}
		
		$videos = array();

		$searchString = "Search Videos";
		if(isset($_GET['search']) && trim($_GET['search']) != '')
		{
			$pageStartIndex = 0;
			if(isset($_GET['page']))
				$pageStartIndex = ($_GET['page'] - 1) * $VIDS_PER_PAGE;
			$videos = $dbAccess->getVideosFromKeywords($_GET['search'], $sortValue, $pageStartIndex, $VIDS_PER_PAGE);
			$searchString = $_GET['search'];
			$dbAccess->logSearchTerm($_GET['search']);
			
			
			// Get the number of videos to show pager
			$totalNumOfVideos = $dbAccess->getNumberOfVideosFromKeywords($_GET['search']);
			$numOfPages = ($VIDS_PER_PAGE + $totalNumOfVideos) / $VIDS_PER_PAGE;
		}
		else if(isset($_GET['random']))
		{
			$videos = $dbAccess->getRandomVideos(5);
		}
		else
		{
			// There is no search
			// Get featured or random or by date or something
			$videos = $dbAccess->getVideos(0, 5, $sortValue);
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
		
		<div id="mainSortBy">
			<table>
				<tr>
					<td>
						Sort by:
					</td>
					<td>
						<form>
							<select style="width: 200px;"   
								onchange="loadPage(this.form.elements[0])"
								target="_parent._top"
								onmouseclick="this.focus()"
								SelectedValue="<?php echo($sortValue); ?>" >
							
								<?php
									// This block of code builds the sort selection box
									//  and selects the current sort option from the get params.
									//$sortOptions = array( 'Date Added', 'Number of Views' );
									$sortOptions = array( 'Date Added', 'Times Viewed', 'Most Liked' );
									foreach($sortOptions as $sortOption)
									{
										echo("<option value=\"" . $sortOption . "\" ");
										if($sortValue == $sortOption)
											echo("selected=\"selected\" ");
										echo(">" . $sortOption . "</option>");
									}
								?>
							</select>
						</form>
					</td>
				</tr>
			</table>
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
	  
  
		<!-- Start VideoLightBox.com BODY section -->
		<div id="mainVideoDisplay">
			<div id="videogallery">
				<?php
					showVideos($videos, $dbAccess);
				?>
                <table><tr>
					<?php
                        // Show the pager
                        if($numOfPages > 1)
                        {
                            for($i = 1; $i < $numOfPages; $i++)
                            {
                                echo("<td><a href=\"index.php?search=" . $_GET['search'] . "&page=" . $i . "\">" . $i . "</a></td>");
                            }
                        }
                    ?>
                </tr></table>
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