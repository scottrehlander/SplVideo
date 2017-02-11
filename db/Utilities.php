<?php

function includeVideoLightBoxHead()
{
	echo("<!-- video stuff -->" .
		"<link rel=\"shortcut icon\" href=\"favicon.ico\" />" .

		"<!-- Start VideoLightBox.com HEAD section -->" .

		"<link rel=\"stylesheet\" href=\"engine/css/videolightbox.css\" type=\"text/css\" />" .
		"<style type=\"text/css\">#videogallery a#videolb{display:none}</style>" .

		"<link rel=\"stylesheet\" type=\"text/css\" href=\"engine/css/overlay-minimal.css\"/>" .
		"<script src=\"engine/js/jquery.tools.min.js\" type=\"text/javascript\"></script>" .
		"<script src=\"engine/js/swfobject.js\" type=\"text/javascript\"></script>" .
		"<script src=\"engine/js/videolightbox.js\" type=\"text/javascript\"></script>" .

		"<!-- End VideoLightBox.com HEAD section -->");
}

function showLightBoxVideo($videoName, $url, $thumbnailUrl)
{
	// http://i3.ytimg.com/vi/6lQr66E6Y_U/default.jpg
	$id = getIdFromUrl($url);
	
	$url = "http://www.youtube.com/v/" . $id;
	
	$thumbnailUrl = "http://i2.ytimg.com/vi/" . $id . "/default.jpg";
	echo("<a rel=\"#voverlay\" href=\"" . $url . "\" title=\"" . $videoName . "\" >" .
		"<img alt=\"spl video car audio spl video\" src=\"" . $thumbnailUrl . "\" alt=\"" . $videoName . "\" /><span></span></a>");
		
}

function showEmbeddedYtVideo($url)
{
	//echo("<object width=\"640\" height=\"385\">");
		//echo("<param name=\"movie\" value=\"http://www.youtube.com/v/" . getIdFromUrl($url) . "&amp;hl=en_US&amp;fs=1\"></param>");
		//echo("<param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param>");
		echo("<div>");
		echo("<embed src=\"http://www.youtube.com/v/" . getIdFromUrl($url) . "&amp;hl=en_US&amp;fs=1\" type=\"application/x-shockwave-flash\"  allowfullscreen=\"true\" width=\"640\" height=\"385\">");
		echo("</embed>");
		echo("</div>");
	//echo("</object>");
}

function getThumbnailString($url)
{
	$id = getIdFromUrl($url);
	$thumbnailUrl = "http://i2.ytimg.com/vi/" . $id . "/default.jpg";
	
	return "&lt;img border=\"0\" alt=\"SPL Videos at Splvideos.com\" src=\"" . $thumbnailUrl . "\" /&gt;";
}

function showYtThumb($url, $link)
{
	$id = getIdFromUrl($url);
	$url = "http://www.youtube.com/v/" . $id;
	$thumbnailUrl = "http://i2.ytimg.com/vi/" . $id . "/default.jpg";
	
	echo("<a href=\"" . $link . "\"><img alt=\"spl videos car audio videos spl\" src=\"" . $thumbnailUrl . "\" /></a>");
}

function showVideos($videos, $dbAccess)
{
	foreach($videos as $video)
	{
		$keywords = $dbAccess->getKeywordsFromVideoId($video['videoId']);
		$keywordString = "";
		foreach($keywords as $keyword)
		{
			$keywordString .= " " . $keyword['keyword'];
		}
		
		echo("<table><tr><td width=\"75px\"><h1><b>Spl Video:</h1></b></td><td><h1>" . $video['videoName'] . "</h1></td></tr></table>");
		echo("<div id=\"videoRow\">");
		echo("<table><tr>");
		echo("<td>");
			showYtThumb($video['videoUrl'], 'Watch.php?video=' . $video['videoId']);
		echo("</td><td>");
			echo("<p style=\"text-align: left;\">" . $video['videoDescription'] . "</p>");
		echo("</td><td>");
			// Grab the date
			$date = explode(' ', $video['dateAdded']);
			$date = explode('-', $date[0]);
			$date = $date[1] . '/' . $date[2] . '/' . $date[0];
			echo("<div style=\"vertical-align: top;font-size:11px;height: 90px;\">" . $date . "</div>");
		echo("</td></tr><tr><td colspan=\"3\">");
		echo("<div style=\"vertical-align: bottom;text-align:right;font-size:8px;height: 90px;\">" . "Keywords: " . $keywordString . "</div>");
		echo("</td></tr></table>");
		echo("</div>");
		
		echo("<div id=\"spacer\"></div>");
	}
}

function getIdFromUrl($url)
{
	// http://i3.ytimg.com/vi/6lQr66E6Y_U/default.jpg
	$explodedUrl = explode('v=', $url);

	$id = explode('?', $explodedUrl[1]);
	$id = explode('&', $id[0]);
	
	return $id[0];
}

function addAnalytics()
{
	echo("<script type=\"text/javascript\">" .
	  "var _gaq = _gaq || [];" .
	  "_gaq.push(['_setAccount', 'UA-17667692-3']);" .
	  "_gaq.push(['_trackPageview']);" .
	  "(function() {" .
		"var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;" .
		"ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';" .
		"var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);" .
	  "})();" .
	"</script>");
}

?>