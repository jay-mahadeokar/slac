<?php


	echo "Reverse geocode functionality <br/>";
	echo reverseGeocodeCity(21.16,79.06);
	var_dump( getWeatherInfo("Nagpur"));

function reverseGeocodeCity($lat,$lon)
{
	$url = "http://where.yahooapis.com/geocode?location=".$lat."+".$lon."&gflags=R&flags=J&appid=".$appid;
	
	$response = getResultFromURL($url);
	
	$results = $response->ResultSet->Results;
			
	return  $results[0]->city ;
	

}


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
