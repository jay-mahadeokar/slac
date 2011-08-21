<?php

	//echo var_dump(getUpcomingEvents("London"));

	/*echo "Reverse geocode functionality <br/>";
	echo reverseGeocodeCity(21.16,79.06)."<br/>";
	$response = reverseGeocodeJSON(21.16,79.06);
	var_dump( $response);
	echo "<br/>".$response->ResultSet->Results[0]->woeid;*/
	/*$response = getTwitterTrends(1);
    $results = $response->query->results->matching_trends->trends->trend;

    foreach ($results as $result) {
		$title = $result->query;
		$content = $result->content;
		//$image_url = $result->url;
		echo   $title . "  ".$content."<br/>";	
	}*/
	//echo reverseGeocodeCity(21.16,79.06);//var_dump(getGoogleNews("Kanpur"));
	//getGoogleNews("Nagpur");
	
	//echo "Reverse geocode functionality <br/>";
	//echo reverseGeocodeCity(21.16,79.06);
	//->rss->channel->item->description
	//var_dump( getUpcomingEvents("Nagpur")->query->results->event);
include_once '../config.php';


function getUpcomingEvents($city)
{
	$BASE_URL = "https://query.yahooapis.com/v1/public/yql";
 
	// Form YQL query and build URI 7to YQL Web service
    $yql_query = "select name from upcoming.events where woeid in (select woeid from geo.places where text='".$city."') | sort(field='start_date') | truncate(count=4)";
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
    $response = getResultFromURL($yql_query_url);
    return $response;
	
}


/*
 * Returns json object with fields which contains array of news items - title,content
 */
function getGoogleNews($keyword)
{
	$BASE_URL = "https://query.yahooapis.com/v1/public/yql";
 
	// Form YQL query and build URI to YQL Web service
    $yql_query = "select title,content from google.news where q='$keyword'";
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
    $response = getResultFromURL($yql_query_url);
    
    /*$results = $response->query->results->results;

    foreach ($results as $result) {
		$title = $result->title;
		$content = $result->content;
		//$image_url = $result->url;
		echo   $title . "  ".$content."<br/>";	
	}*/
    return $response;
}



/*
 * Returns the weather json object according to the city name passed
 * 
 */ 
function getWeatherInfo($city)
{
	$BASE_URL = "https://query.yahooapis.com/v1/public/yql";
 
	// Form YQL query and build URI to YQL Web service
    $yql_query = "select * from weather.bylocation where location='$city'";
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
    $result = getResultFromURL($yql_query_url);
    //var_dump($result);
    return $result;
}
/*


function reverseGeocodeJSON($lat,$lon)
{
	$url = "http://where.yahooapis.com/geocode?location=".$lat."+".$lon."&gflags=R&flags=J&appid=".$appid;
	
	$response = getResultFromURL($url);
			
	return  $response ;
}
*/


/*
 * Returns the latest twitter trends acc to woeid passed.
 */
function getTwitterTrends($city)
{
	$BASE_URL = "https://query.yahooapis.com/v1/public/yql";
 
	// Form YQL query and build URI 7to YQL Web service
    $yql_query = "select * from twitter.trends.location where woeid in (select woeid from geo.places where text='".$city."')";
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
    $response = getResultFromURL($yql_query_url);
    return $response;
	
}	
	
/*
 * Returns the city acc to lat lon passed.
 * 
 */
function reverseGeocodeCity($lat,$lon)
{
	$url = "http://where.yahooapis.com/geocode?location=".$lat."+".$lon."&gflags=R&flags=J&appid=".$appid;
	
	$response = getResultFromURL($url);
	$results = $response->ResultSet->Results;
			
	return  $results[0]->city ;
}


/*
 * Main Curl utility function
 */
function getResultFromURL($url) 
{
	$session = curl_init($url);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($session);
	//var_dump($json);
	curl_close($session);
	
	return json_decode($json);
}


?>
