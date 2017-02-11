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
		parse_str(parse_url($url, PHP_URL_QUERY), $query);


		$explodedUrl = explode('v=', $url);
	
		die($explodedUrl[1]);
	
	$thumbnailUrl = "http://i2.ytimg.com/vi/" . substr($url[4], 8);
	echo("<a rel=\"#voverlay\" href=\"" . $url . "\" title=\"" . $videoName . "\" >" .
		"<img src=\"" . $thumbnailUrl . "\" alt=\"" . $videoName . "\" /><span></span></a>");
		
}

?>