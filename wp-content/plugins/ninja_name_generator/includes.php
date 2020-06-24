<?php 
add_action( 'init', 'script_enqueuer' );


//Basic wordpress function to declare script 
function script_enqueuer() {
   wp_register_script( "generator", WP_PLUGIN_URL.'/ninja_name_generator/generator.js', array('jquery') );
   wp_localize_script( 'generator', 'name_generator', array( 
   		'restURL' => rest_url(),
   		'restNonce' => wp_create_nonce("wp_rest")
   	));

   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'generator' );

}