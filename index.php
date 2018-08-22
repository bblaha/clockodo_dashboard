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

function getUserClockObject($userid){
	return json_decode(callAPI("GET","https://my.clockodo.com/api/clock?users_id=".$userid, getPayload()), true);
}

function isWorking($userid){

	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/clock?users_id=".$userid, getPayload()), true);
	return !is_null($jsonTxt["running"]);
}

function isWorkingObject($clockObject){
	return !is_null($clockObject["running"]);
}

function getService($userid){

	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/clock?users_id=".$userid, getPayload()), true);
	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/services/".$jsonTxt["running"]["services_id"], getPayload()), true);
	return $jsonTxt["service"]["name"];
}

function getServiceObject($clockObject){
	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/services/".$clockObject["running"]["services_id"], getPayload()), true);
	return $jsonTxt["service"]["name"];
}

function getUsers(){
	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/users", getPayload()), true);
	return $jsonTxt["users"];
}

function getAbsences(){
	$jsonTxt = json_decode(callAPI("GET","https://my.clockodo.com/api/absences?year=".date("Y"), getPayload()), true);
	return $jsonTxt;
}

function main(){
	
	$admin = $_GET["admin"];
	$users = getUsers();
	foreach($users as $user){
		if($user["active"]==true){
		$clockObject = getUserClockObject($user["id"]);
		$working = isWorkingObject($clockObject);
		?>
			<div class="namebox<?php if($working){echo " active";}; ?>">
		<?php echo $user["name"]; ?><br />
		<?php if($working&&$admin){echo "<p class=\"service\">(".getServiceObject($clockObject).", ".$clockObject["running"]["duration_time"].")</p>";}; ?>
			</div>
		<?php
		}
	}
	if($admin){
		echo date("Y");
		var_dump(getAbsences());
	}
	
}

?>
<html>
<head>
<META HTTP-EQUIV="refresh" CONTENT="20">
<style>
body{
	background-color:black;
	color:white;
	font-size:1.5em;
	font-family: Arial;
	}
.service{
	font-size:0.36em;
}
.namebox{
	display:block;
	float:left;
	background-color:red;
	width:200px;
	height:75px;
	margin:5px;
	text-align:center;
	vertical-align:center;
}
.active{
	background-color:green;
}
</style>
</head>
<body>
	<?php main() ?>
</body>
</html>