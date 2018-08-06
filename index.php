<?php
function callAPI($method, $url, $data){
   $curl = curl_init();
   
   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         break;
      case "PUT":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
         break;
      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }
   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, $url);
   /*curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'APIKEY: 111111111111111111111',
      'Content-Type: application/json',
   ));*/
   curl_setopt($curl, CURLOPT_HTTPHEADER, $data);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){die("Connection Failure");}
   curl_close($curl);
   return $result;
}

function getPayload(){
		
	$mail = $_GET["mail"];
	$key = $_GET["key"];
	$payload = array(
      'X-ClockodoApiUser: '.$mail,
      'X-ClockodoApiKey: '.$key,
   );
   
   return $payload;
}

function isWorking($userid){

   
	$json = json_decode(callAPI("GET","https://my.clockodo.com/api/clock/".$userid, getPayload()));
	
}

function getUsers(){
	$json = json_decode(callAPI("GET","https://my.clockodo.com/api/users", getPayload()));
	return $json;
}

function main(){
	$mail = $_GET["mail"];
	$key = $_GET["key"];
	$payload = array(
      'X-ClockodoApiUser: '.$mail,
      'X-ClockodoApiKey: '.$key,
   );
	echo getUsers();
}
	main();
?>