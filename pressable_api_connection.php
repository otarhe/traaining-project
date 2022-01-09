<?php
/*
Plugin Name: Pressable API connection
Description: Demonstrating how to access the Pressable API using a simnple plugin
Plugin URI:  https://pressable.com/
Author:      Obatarhe Otughwor
Version:     1.0
*/



// add top-level administrative menu
function pressable_api_toplevel_menu() {

	add_menu_page(
		esc_html__('Pressable API Connection', 'pressable-api-connection'),
		esc_html__('Pressable API Connection Test', 'pressable-api-connection'),
		'manage_options',
		'pressable-api-connection',
		'pressable_api_display_settings_page',
		'dashicons-admin-generic',
		null
	);

}
add_action( 'admin_menu', 'pressable_api_toplevel_menu' );


function pressable_api_display_settings_page() {

// display the plugin settings page


	?>

	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	</div>

<?php



//Connecting to Pressable API
$pressable_api_request_headers = array(
	//Add your Bearer Token
   'Authorization' => 'Bearer ' . ( 'Add-Your-Bearer-Token-Here' )
);

//Pressable API request URL example: https://my.pressable.com/v1/sites
$pressable_api_request_url = 'https://my.pressable.com/v1/sites';

//Initiating connection to the API using WordPress request function
$pressable_api_response_get_request = wp_remote_request(
    $pressable_api_request_url,
    array(
    	//You can change the API method to suit your needs
        'method'    => 'GET',
        'headers'   => $pressable_api_request_headers
    )
);

//initiating connection to the API using WordPress request function
$pressable_api_response_post_request = wp_remote_request(
    $pressable_api_request_url,
    array(
    	//You can change the API method to suit your needs
        'method'    => 'POST',
        'headers'   => $pressable_api_request_headers
    )
);

//Display request using API code
echo '<strong>Connection status code -</strong> ' . wp_remote_retrieve_response_code( $pressable_api_response_get_request ) . ' ' . wp_remote_retrieve_response_message( $pressable_api_response_get_request );
echo '<strong>Response Body</strong>';
echo wp_remote_retrieve_body( $pressable_api_response_get_request );
}
