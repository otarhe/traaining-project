<?php

/*
Plugin Name: Pressable API connection web application
Description: Demonstrating how to access the Pressable API using a simple web application
Plugin URI:  https://pressable.com/
Author:      Obatarhe Otughwor
Version:     1.0
*/


$curl = curl_init();
$auth_data = array(
  'client_id'     => 'ADD-CLIENT-ID-HERE',
  'client_secret'   => 'ADD-CLIENT-SECRET-HERE',
  'grant_type'    => 'client_credentials'
);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
curl_setopt($curl, CURLOPT_URL, 'https://my.pressable.com/auth/token');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
$results = curl_exec($curl);
if(!$results){die("Connection Failure");}
curl_close($curl);


$results = json_decode($results, true);

 echo "Bearer token:" . '<br/>';
 $res = print_r($results["access_token"]);

 


echo $res;

echo " </br>";
echo " </br>";
echo " </br>";

$token = $results["access_token"];
$b = 'Authorization: Bearer ';

$curl = curl_init();

 
curl_setopt_array($curl, array(
  //Pressable API request URL example: https://my.pressable.com/v1/sites
  CURLOPT_URL => "https://my.pressable.com/v1/sites/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  //You can change the request type from here
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    $b  . $token,     
    "cache-control: no-cache"
  ),
));
 
$response = curl_exec($curl);
$err = curl_error($curl);
 
curl_close($curl);

 echo "Responses:" . '<br/>';

//Decode result to json format
 $results = json_decode($response, true);

    echo '<pre>';
    print_r($results);
