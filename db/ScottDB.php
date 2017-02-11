<?php

	class DbAccess
	{
		protected $pdo;
		
		function getPdo()

		{
			if(empty($this->pdo))
			{
				$connectDb = "mysql:host=db2549.perfora.net;dbname=db336345575";
				$this->pdo = new pdo($connectDb, "dbo336345575", "videos1234");
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			return $this->pdo;
		}
		
		
		// We want to pass in the query and an associative array of variables
		//  Bind the variables and then execute the query.
		function ExecutePreparedQuery($sql, $vars)
		{
			try
			{
				$query = $this->getPdo()->prepare($sql);
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
				print_r($this->getPdo()->errorInfo);
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
			$connectDb = "mysql:host=db2549.perfora.net;dbname=db336345575";
			
			try
			{
				$this->getPdo()->beginTransaction();
				$query = $this->getPdo()->prepare($sql);

				$query->Execute($vars);
				$this->getPdo()->commit();
			}
			catch(Exception $ex) { print_r($ex); die(); $this->getPdo()->rollbackTransaction(); }
		}
				
		function GetLastInsertId()
		{	
			return $this->getPdo()->lastInsertId();
		}
		
		
		// Select functions
		function getCategories()
		{
			$sql = "SELECT * FROM categories ORDER BY sequence";
			$result = $this->ExecutePreparedQuery($sql, array());
			
			$categories = array();
			foreach($result as $row)
			{
				$categories[] = $row;
			}
			
			return $categories;
		}
		
		function getVideos($startVideoIndex, $numOfVideos, $sortString)
		{
			$sql = "SELECT * FROM videos";
			if($sortString == "Date Added")
				$sql .= " ORDER BY dateAdded DESC";
			else if($sortString == "Times Viewed")
				$sql .= " ORDER BY timesViewed DESC";

			$sql .= " limit " . $numOfVideos;
			
			
			$result = $this->ExecutePreparedQuery($sql, array());
			
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
			$result = $this->ExecutePreparedQuery($sql, array());
			
			$videos = array();
			foreach($result as $row)
			{
				$videos[] = $row;
			}
			
			return $videos;
		}
		
		function getRandomVideos($numOfVideos)
		{
			$sql = "SELECT * FROM videos ORDER BY Rand() LIMIT " . $numOfVideos;
			$result = $this->ExecutePreparedQuery($sql, array());
			
			$videos = array();
			foreach($result as $row)
			{
				$videos[] = $row;
			}
			
			return $videos;
		}
				
		function createKeywordSelect($keywords)
		{
			$sql = "SELECT distinct * FROM keywords k inner join videos v on v.videoId = k.videoId where ";
			foreach($keywords as $keyword)
			{
				if(trim($keyword == "")) continue;
				
				$sql .= "k.keyword like '%" . $keyword . "%' or ";
			}
			
			// Get rid of the last'
			$sql = substr($sql, 0, strlen($sql) - 4);
			return $sql;
		}		
		
		function getVideosFromKeywords($keywords, $sortString, $numOfVideos)
		{
			//This accepts a string of space separated keywords
			$keywords = explode(" ", $keywords);

			$vars = array();
			$sql = $this->createKeywordSelect($keywords);
			
			$sql .= " GROUP BY v.videoId";
			
			if($sortString == 'Times Viewed')
				$sql .= " ORDER BY timesViewed DESC";
			else if($sortString == 'random')
				$sql .= " ORDER BY Rand()";
				
			if(isset($numOfVideos))
				$sql .= " LIMIT " . $numOfVideos;
			
			$result = $this->ExecutePreparedQuery($sql, $array);
			
			$videos = array();
			foreach($result as $row)
			{
				$videos[] = $row;
			}
			
			return $videos;
		}
		
		function getVideoById($id)
		{			
			$sql = "SELECT * FROM videos WHERE videoId = :id";
			$vars = array( ':id'=>$id );
			$result = $this->ExecutePreparedQuery($sql, $vars);

			return $result[0];
		}
		
		function getKeywordsFromVideoId($id)
		{
			$sql = "SELECT * FROM keywords k WHERE videoId = :id";
			$vars = array ( ':id'=>$id );
			$result = $this->ExecutePreparedQuery($sql, $vars);
			
			$keywords = array();
			foreach($result as $row)
			{
				$keywords[] = $row;
			}
			
			return $keywords;
		}
		
		
		
		
		
		// Insert and Update
		
		function addVideo($dataArray)
		{
			try
			{
				$this->getPdo()->beginTransaction();
				
				$sql = "INSERT INTO videos (videoName, videoUrl, videoDescription, isFeatured) values " .
					"(:videoName, :videoUrl, :videoDescription, :isFeatured)";
					
				$vars = array(
					':videoName'=>$dataArray['videoName'],
					':videoUrl'=>$dataArray['videoUrl'],
					':videoDescription'=>$dataArray['videoDescription'],
					':isFeatured'=>$dataArray['isFeatured']
				);
				
				$result = $this->ExecutePreparedQuery($sql, $vars);
				
				// Get the id for the video
				$id = $this->GetLastInsertId();
				
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
					$result = $this->ExecutePreparedQuery($sql, $vars);
					
					$usedKeywords[] = trim($keyword);
				}
				
				$this->getPdo()->commit();
			}
			catch(Exception $ex) { $this->getPdo->rollback(); var_dump($ex); die();  }
		}
		
		function logSearchTerm($search)
		{
			$sql = "INSERT INTO searchTermHistory (searchTerm) values (:searchTerm)";
			$vars = array ( ':searchTerm'=>$search );
			$result = $this->ExecutePreparedQuery($sql, $vars);
		}
	
	
	
	
	
		// Video Stats
	
		function incrementTimesWatched($videoId)
		{
			$sql = "UPDATE videos set timesViewed = timesViewed + 1 where videoId = :videoId";
			$vars = array ( ':videoId'=>$videoId);
			$this->ExecutePreparedUpdateQuery($sql, $vars);
		}	
	
		function insertRating($videoId, $rating)
		{
			try
			{
				//$this->getPdo()->beginTransaction();
				
				if($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) return;
				
				if($this->didIpRate($videoId))
				{
					// UPDATE
					$sql = "UPDATE videoRatings SET rating = :rating WHERE videoId = :videoId AND ipAddress = :ipAddress";
					$vars = array( 'videoId'=>$videoId, ':rating'=>$rating, ':ipAddress'=>$_SERVER['REMOTE_ADDR'] );
					$result = $this->ExecutePreparedUpdateQuery($sql, $vars);
				}
				else
				{
					// INSERT
					$sql = "INSERT INTO videoRatings (videoId, rating, ipAddress) values (:videoId, :rating, :ipAddress)";
					$vars = array( 'videoId'=>$videoId, ':rating'=>$rating, ':ipAddress'=>$_SERVER['REMOTE_ADDR'] );
				}
					$result = $this->ExecutePreparedQuery($sql, $vars);
					
				//$this->getPdo()->commit();
			}
			catch(Exception $ex) { /*$this->getPdo->rollback();*/ var_dump($ex); die();  }
			
		}

		function didIpRate($videoId)
		{
			// See if this user rated this video already
			$sql = "SELECT ipAddress FROM videoRatings WHERE ipAddress = :ipAddress AND videoId = :videoId";
			$vars = array ( 'ipAddress'=>$_SERVER['REMOTE_ADDR'], 'videoId'=>$videoId );
			$result = $this->ExecutePreparedQuery($sql, $vars);
			
			return count($result) > 0;
		}
		
	};
	
	
?>