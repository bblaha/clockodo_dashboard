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

	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/clock?users_id=".$userid, getPayload()), true);
	return !is_null($jsonTxt["running"]);
}

function getUsers(){
	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/users", getPayload()), true);
	return $jsonTxt["users"];
}

function main(){
	$mail = $_GET["mail"];
	$key = $_GET["key"];
	$payload = array(
      'X-ClockodoApiUser: '.$mail,
      'X-ClockodoApiKey: '.$key,
   );
	$users = getUsers();
	foreach($users as $user){
		?>
			<div class="namebox<?php if(isWorking($user["id"])){echo " active"}; ?>">
		<?php echo $user["name"]; ?>
			</div>
		<?php
	}
	
	
}

?>
<html>
<head>
<style>
body{
	background-color:black;
	color:white;
	font-size:1.5em;
	}
#namebox{
	display:block;
	float:left;
	background-color:red;
	width:200px;
	height:75px;
	margin:5px;
}
#active{
	background-color:green;
}
</style>
</head>
<body>
	<?php main() ?>
</body>
</html>