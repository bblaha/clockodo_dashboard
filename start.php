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


function main(){


	$jsonTxt = json_decode(callAPI("POST","https://my.clockodo.com/api/clock?customers_id=".$_GET["cid"]."&services_id=".$_GET["sid"]."&billable=0", getPayload()), true);
	var_dump($jsonTxt);
	
}

?>
<html>
<head>
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