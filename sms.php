<?php
$varUserName=username;
$varPWD=password;
$varSenderID=senderID;
$varPhNo="9811xxxxxx,9812xxxxxx";
$varMSG="message to send";
$url="http://www.yourdomain.com/api/swsend.asp";
$data="username=".$varUserName."&password=".$varPWD."&sender=".$varSenderID."&sendto=".$va
rPhNo."&message=".$varMSG;
postData($url,$data);
function postdata($url,$data)
{
//The function uses CURL for posting data to
$objURL = curl_init($url); curl_setopt($objURL,
CURLOPT_RETURNTRANSFER,1);
curl_setopt($objURL,CURLOPT_POST,1);
curl_setopt($objURL, CURLOPT_POSTFIELDS,$data);
$retval = trim(curl_exec($objURL));
curl_close($objURL);
return $retval;
}
?>
