<?php

	$pdo;
	$dbServer = "db2549.perfora.net";
	$dbName = "db336345575";
	$dbUser = "dbo336345575";
	$dbPassword = "videos1234";
	
	// We want to pass in the query and an associative array of variables
	//  Bind the variables and then execute the query.
	function ExecutePreparedQuery($sql, $vars)
	{
		if(empty($pdo))
		{
			$connectDb = "mysql:host=" . $dbServer . ";dbname=" . $dbName;
			$pdo = new pdo($connectDb, $dbUser, $dbPass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		
		try
		{
			$query = $pdo->prepare($sql);
		}
		catch(Exception $e)
		{
			echo("<b><font color=\"red\">Error executing sql query:</font><br><br>");
			print_r($e->getMessage());
			die();
		}
		if($query)
			$query->execute($vars);
		else
		{
			print_r($pdo->errorInfo);
			die();
		}
		
		try
		{
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{	// An insert will throw, this is crappy and should probably be handled better
			return "";
		}
	}
	
	function ExecutePreparedUpdateQuery($sql, $vars)
	{
		$connectDb = "mysql:host=" . $dbServer . ";dbname=" . $dbName;
		
		if(empty($pdo))
			$pdo = new pdo($connectDb, $dbUser, $dbPass);
		
		try
		{
			$pdo->beginTransaction();
			$query = $pdo->prepare($sql);

			$query->Execute($vars);
			$pdo->commit();
		}
		catch(Exception $ex) { print_r($ex); die(); $pdo->rollbackTransaction(); }
	}
	
	
	
	// Select functions
	function getCategories()
	{
		$sql = "SELECT * FROM categories ORDER BY sequence";
		$vars = array();
		$result = ExecutePreparedQuery($sql, $vars);
		
		
		$categories = array();
		foreach($result as $row)
		{
			$categories[] = $row;
		}
		
		return $categories;
	}
	
	function getVideosByDate($startVideoIndex, $numOfVideos)
	{
		$sql = "SELECT * FROM videos";
		$vars = array();
		$result = ExecutePreparedQuery($sql, $vars);
		
		$videos = array();
		foreach($result as $row)
		{
			$videos[] = $row;
		}
		
		return $videos;
	}
	
	function getRandomFeaturedVideos($numOfVideos)
	{
		$sql = "SELECT * FROM videos WHERE isFeatured = 1 ORDER BY Rand() LIMIT " . $numOfVideos;
		$vars = array();
		$result = ExecutePreparedQuery($sql, $vars);
		
		$videos = array();
		foreach($result as $row)
		{
			$videos[] = $row;
		}
		
		return $videos;
	}
	
	function getVideosFromKeywords($keywords)
	{
		//This accepts a string of space separated keywords
		// $keywords = explode(" ", $keywords);
		// $vars = 
		// $sql = "SELECT distinct * FROM keywords k inner join videos v on v.videoId = k.videoId where ";
		
		// foreach($keywords as $keyword)
		// {
			// if(trim($keyword == "")) continue;
			
			// $sql .= "k.keyword like '%" . $keyword . "%'";
			// if($keyword != $keywords[count($keywords) - 1])
				 // $sql .= " or ";
		// }
		
		// $sql .= " GROUP BY v.videoId";
		
		// $result = ExecutePreparedQuery($sql);
		
		// $videos = array();
		// foreach($result as $row)
		// {
			// $videos[] = $row;
		// }
		
		// return $videos;
	}
	
	
	// Insert and Update
	
	function addVideo($con, $dataArray)
	{
		$sql = "INSERT INTO videos (videoName, videoUrl, videoDescription, isFeatured) values " .
			"(:videoName, :videoUrl, :videoDescription, :isFeatured)";
			
		$vars = array(
			':videoName'=>$dataArray['videoName'],
			':videoUrl'=>$dataArray['videoUrl'],
			':videoDescription'=>$dataArray['videoDescription'],
			':isFeatured'=>$dataArray['isFeatured']
		);
		$result = ExecutePreparedQuery($sql, $vars);
		
		// Get the id for the video
		$id = mysql_insert_id();
		
		// Grab the keywords
		$keywords = explode(' ', $dataArray['keywords']);
		
		$usedKeywords = array();
		foreach($keywords as $keyword)
		{
			// If this keyword is blank, or has already been put
			//  in the db for this video, continue
			if(trim($keyword) == '' ||
				in_array($keyword, $usedKeywords)) continue;
			$sql = "INSERT INTO keywords (keyword, videoId) values (:keyword, :videoId)";
			$vars = array(
				':keyword'=>trim($keyword),
				':videoId'=>$id
			);
			$result = ExecutePreparedQuery($sql, $vars);
			
			$usedKeywords[] = trim($keyword);
		}
	}
	
?>