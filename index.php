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
<meta name="msvalidate.01" content="5D621D4C1102C30690D9BB7FF106975F" />
<meta name="language" content="english" />
<meta name="description" content="The best place to browse SPL videos.  View some extremely high decible level car audio SPL videos here.  Check out dbDrag hair tricks, USACi shattered windshields, IASCA spl runs, massive car audio SPL competition systems, etc..  Check out new car audio equipment SPL including mid-level speakers, subwoofers, amplifiers, capacitors, head units, in-dash navigation, and more! All at Splvideos.com!" />
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
	
    <div id="rss">
		<!-- Feedo Style code version: X.1.
				Copyright 2005-2009 Acme Inc..
		More info available at http://www.feedostyle.com/ -->
		<script type="text/javascript">
		//<![CDATA[

		feedostyle_id = "832428ea7b424915a0418962becca187";
		feedostyle_format = "vertical_marquee";
		feedostyle_show_date = true;
		feedostyle_show_time = true;
		feedostyle_font_size = "medium";
		feedostyle_font_family = "auto";
		feedostyle_width = "135px";
		feedostyle_height = "240px";
		feedostyle_entry_count = 5;
		feedostyle_new_window_links = true;

		//]]>
		</script>
		<script type="text/javascript" src="http://digest.feedostyle.com/feedostyle.js"></script>
		<!-- End Feedo Style code version: X.1. -->
	</div>

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

		$searchString = "Search Spl Videos";
		if(isset($_GET['search']) && trim($_GET['search']) != '')
		{
			$videos = $dbAccess->getVideosFromKeywords($_GET['search'], $sortValue, 10);
			$searchString = $_GET['search'];
			$dbAccess->logSearchTerm($_GET['search']);
		}
		else if(isset($_GET['random']))
		{
			$videos = $dbAccess->getRandomVideos(15);
		}
		else
		{
			// There is no search
			// Get featured or random or by date or something
			$videos = $dbAccess->getRandomVideos(15);
		}

	?>

  <div id="content">
	
	<img src="smallLogo.png" style="top:0px;left:0px" alt="spl videos car audio spl videos" />
  
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
			<br /><br />
			<a href="http://www.facebook.com/pages/Competition-Car-Audio-SPL-Videos/147158291974686?v=info" target="_blank">Become a fan on Facebook!</a>
            <br/><br/>
			<a href="http://splvideos.com/forum">Spl Video Forum</a>
			<br/><br/>
			<a href="http://golf-training-videos.com">Golf-Training-Videos.com</a>
			
		</li>

		</ul>
	  </div>
	 
		<div id="facebookWidget">
			<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FCompetition-Car-Audio-SPL-Videos%2F147158291974686%3Fv%3Dinfo&amp;width=200&amp;colorscheme=light&amp;connections=10&amp;stream=true&amp;header=true&amp;height=587" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:587px;" allowTransparency="true"></iframe>
		</div>
	 
  
		<!-- Start VideoLightBox.com BODY section -->
		<div id="mainVideoDisplay">
			<div id="videogallery">
				<?php
					showVideos($videos, $dbAccess);
				?>
			</div>
			<!-- End VideoLightBox.com BODY section -->
		</div>
		
		<div id="ads">
			<h2>Spl Videos Ads</h2>
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


<div id="eXTReMe"><a href="http://extremetracking.com/open?login=xi2elic">
<img src="http://t1.extreme-dm.com/i.gif" style="border: 0;"
height="38" width="41" id="EXim" alt="eXTReMe Tracker" /></a>
<script type="text/javascript"><!--
EXref="";top.document.referrer?EXref=top.document.referrer:EXref=document.referrer;//-->
</script><script type="text/javascript"><!--
var EXlogin='xi2elic' // Login
var EXvsrv='s10' // VServer
EXs=screen;EXw=EXs.width;navigator.appName!="Netscape"?
EXb=EXs.colorDepth:EXb=EXs.pixelDepth;EXsrc="src";
navigator.javaEnabled()==1?EXjv="y":EXjv="n";
EXd=document;EXw?"":EXw="na";EXb?"":EXb="na";
EXref?EXref=EXref:EXref=EXd.referrer;
EXd.write("<img "+EXsrc+"=http://e1.extreme-dm.com",
"/"+EXvsrv+".g?login="+EXlogin+"&amp;",
"jv="+EXjv+"&amp;j=y&amp;srw="+EXw+"&amp;srb="+EXb+"&amp;",
"l="+escape(EXref)+" height=1 width=1>");//-->
</script><noscript><div id="neXTReMe"><img height="1" width="1" alt=""
src="http://e1.extreme-dm.com/s10.g?login=xi2elic&amp;j=n&amp;jv=n" />
</div></noscript></div>

		</div>
		
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>

  </div>
</div>

</body>
</html>