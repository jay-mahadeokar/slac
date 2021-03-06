<?php

/*echo var_dump(getWeatherDetails("Nagpur"));

echo "<br/> Events <br/>";
echo var_dump(getEventDetails("New York"));
echo var_dump(getNewsDetails("Nagpur"));
*/

//echo postLoginAction("jay",21.16,79.06);
//echo postLoginAction("naruto",51.686031,0.280360);
//echo getUserInfo("naruto");


function getUserInfo($userId)
{
	include_once "./utils/geo.php";

	$tmppath= "./.cache/users/".$userId;
	if(file_exists($tmppath))
	{
		$fh=fopen($tmppath,"r");
		$result = fread($fh, 10000);
		return $result;	
	}	
	else
		return "USER IS NOT ONLINE ON OUR SYSTEM!!  Seems (s)he doesn't like to have fun! :P";
	
}

function postLogoutAction($userId)
{
	$tmppath= "./.cache/users/".$userId;
	if($debug) echo $tmppath;
	unlink($tmppath);
}

function postLoginAction($userId, $city)
{
	include_once "./utils/geo.php";
	//$city = reverseGeocodeCity($lat,$lon);
	
	$weather = getWeatherDetails($city);
	$events = getEventDetails($city);
	$news = getNewsDetails($city);
	
	$output = "";
		//Inserting weather info
		$output .= "<h3>Weather at " . $userId . "'s place</h3>";
		$output .= $weather->query->results->weather->rss->channel->item->description;
		
		$output .= "<br /><h3>News, Buzz and stuff.. </h3><br/>";
		$results = $news->query->results->results;
		
		foreach ($results as $result) {
			$output .= "<div class='news'>";
			$output .= "<div class='newstitle'>"; 
			$output .= $result->title;
			$output .= "</div>";
			
			$output .= "<div class='newscontent'>"; 
			$output .= $result->content;
			$output .= "</div>";
			$output .= "</div>";
		}
		
		$results = $events->query->results->event;
		if($results != NULL)
		{
			$output .= "<div class='heading'> Watch out for these events!! </div><br/>";
		
			foreach ($results as $result) {
				$output .= "<div class='newstitle'>"; 
				$output .= $result->name;
				$output .= "</div>";
			
			}	
		}
		
	$tmppath= "./.cache/users/".$userId;
	if(!file_exists($tmppath))
	{
		$fh=fopen($tmppath,"w+");
		fwrite($fh, $output);
		fclose($fh);	
		//return $output;
	}	
		
}

function getWeatherDetails($city)
{
	include_once "./utils/geo.php";
	
	$tmppath= "./.cache/cities/".$city;
	
	if(!file_exists($tmppath))
	{
		 mkdir($tmppath);
	}
		if(!file_exists("$tmppath/weather"))
		{
			//echo "Creating weather file";
			$fh=fopen("$tmppath/weather","w+");
			$res = getWeatherInfo($city);
			//var_dump($res);
			fwrite($fh, json_encode($res));
			fclose($fh);	
			return $res;
		}
	
		
	$fh=fopen("$tmppath/weather","r");
	$res = fread($fh, 10000);
	return json_decode($res);
	
}


function getEventDetails($city)
{
	include_once "./utils/geo.php";
	
	$tmppath= "./.cache/cities/".$city;
	
	if(!file_exists($tmppath))
	{
		 mkdir($tmppath);
	}
		if(!file_exists("$tmppath/events"))
		{
			//echo "Creating event file";
			$fh=fopen("$tmppath/events","w+");
			$res = getUpcomingEvents($city);
			fwrite($fh, json_encode($res));
			fclose($fh);	
			return $res;
		}
	
		
	$fh=fopen("$tmppath/events","r");
	$res = fread($fh, 10000);
	return json_decode($res);
		
}

function getNewsDetails($city)
{
	include_once "./utils/geo.php";
	
	$tmppath= "./.cache/cities/".$city;
	
	if(!file_exists($tmppath))
	{
		mkdir($tmppath);
	}
		
		if(!file_exists("$tmppath/news"))
		{
			//echo "Creating news file";
			$fh=fopen("$tmppath/news","w+");
		
			$res = getGoogleNews($city);
			fwrite($fh, json_encode($res));
			fclose($fh);	
			return $res;
		}
	
		
	$fh=fopen("$tmppath/news","r");
	$res = fread($fh, 10000);
	return json_decode($res);
		
}

?>
