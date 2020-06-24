<?php
/**
 * @package Ninja name generator
 * @version 1.0
 */
/*
Plugin Name: Ninja name generator
Description: Random ninja name generator made for TLM
Author: Hugo Savard
Version: 1.0
*/

require_once("includes.php");
require_once("helpers.php");

//We declare the Api endpoint for Wordpress 

add_action('rest_api_init', function () {
  register_rest_route( 'ninjify/v1', 'generate/(?P<user_entry>\S+)',array(
                'methods'  => 'GET',
                'callback' => 'name_generator'
      ));
});

//Add some ajax parameter to fetch data from the website 
add_action("wp_ajax_name_generator", "name_generator");
add_action("wp_ajax_nopriv_name_generator", "name_generator");