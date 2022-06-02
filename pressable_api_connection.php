<?php
/*
Plugin Name: Query Pressable API
Description: Demonstrating how to access the Pressable API using a simple WordPress plugin
Plugin URI:  https://pressable.com/
Author:      Obatarhe Otughwor
Version:     1.0
*/

// If this file is access directly, abort!!!
defined('ABSPATH') or die('Unauthorized Access');

session_start();

function pressable_api_get_send_data()
{

    //Check if the access token has expired else generate a new one
    if (time() < $_SESSION['access_token_expiry'])
    {

        $token = get_transient('access_token');
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

        echo '<br/>';
        echo '<br/>';

        echo "<h1>Cached Generated Bearer Token:</h1>" . '<br/>';
        print_r(get_transient('access_token'));

        echo "<h2> Responses:</h2>" . '<br/>';

        //Decode result
        $results = json_decode($response, true);

        echo '<pre>';
        print_r($results);

        return false;

    }
    else
    {

        // Generate access token
        $curl = curl_init();
        $auth_data = array(
            'client_id' => 'ADD-YOUR-CLIENT-ID-HERE',
            'client_secret' => 'ADD-YOUR-CLIENT-SECRECT-HERE',
            'grant_type' => 'client_credentials'
        );
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
        curl_setopt($curl, CURLOPT_URL, 'https://my.pressable.com/auth/token');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $results = curl_exec($curl);
        if (!$results)
        {
            die("Connection Failure");
        }
        curl_close($curl);

        $results = json_decode($results, true);

        echo "<h1>Generated Bearer Token:</h1>" . '<br/>';
        $access_token = print_r($results["access_token"]);

        // Use session to save the access token
        $_SESSION['access_token_expiry'] = time() + $results['expires_in'];
        // Cache the generated access token using transient to reduce uncessary api calls
        set_transient('access_token', $results['access_token'], $results['expires_in']);

        // $token = $results["access_token"];
        $token = get_transient('access_token');
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

        echo '<br/>';
        echo '<br/>';

        echo "<h2>Responses:</h2>" . '<br/>';

        //Decode result
        $results = json_decode($response, true);

        echo '<pre>';
        print_r($results);

        set_transient('pressable-api-reseponse', $results['pressable-api-reseponse'], $results['expires_in']);

        // if ( is_wp_error( $response ) ) {
        // 		$error_message = $response->get_error_message();
        // 		return "Something went wrong accessing the api: $error_message";
        // 	} else {
        // 		echo '<pre>';
        // 		var_dump( wp_remote_retrieve_body( $response ) );
        // 		echo '</pre>';
        // 	}
        
    }
}

// session_destroy();

/**
 * Register a custom menu page to view the information queried.
 */
function pressable_api_register_my_custom_menu_page()
{
    add_menu_page(__('Query Pressable API', 'query-apis') , 'Query Pressable API', 'manage_options', 'api-test.php', 'pressable_api_get_send_data', 'dashicons-testimonial', 16);
}

add_action('admin_menu', 'pressable_api_register_my_custom_menu_page');
