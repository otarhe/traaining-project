<?php
/*
Plugin Name: Pressable API connection
Description: Demonstrating how to access the Pressable API using a simple WordPress plugin
Plugin URI:  https://pressable.com/
Author:      Obatarhe Otughwor
Version:     1.0
*/

// add top-level administrative menu
function pressable_api_toplevel_menu() {

    add_menu_page(esc_html__('Pressable API Connection Test', 'pressable-api-connection') , esc_html__('Pressable API Connection Test', 'pressable-api-connection') , 'manage_options', 'pressable-api-connection', 'pressable_api_display_settings_page', 'dashicons-admin-generic', null);

}
add_action('admin_menu', 'pressable_api_toplevel_menu');

function pressable_api_display_settings_page() {

    // display the plugin settings page
    

    
?>

	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		
	</div>

<?php

    $curl = curl_init();
    $auth_data = array(
        'client_id' => 'ADD-CLIENT-ID-HERE',
        'client_secret' => 'ADD-CLIENT-SECRET-HERE',
        'grant_type' => 'client_credentials'
    );
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
    curl_setopt($curl, CURLOPT_URL, 'https://my.pressable.com/auth/token');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    $results = curl_exec($curl);
    if (!$results) {
        die("Connection Failure");
    }
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
	//You can chnage the request method from GET to POST or any method
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            $b . $token,
            "cache-control: no-cache"
        ) ,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    echo "Responses:" . '<br/>';

    //Decode result
    $results = json_decode($response, true);

    echo '<pre>';
    print_r($results);

}
