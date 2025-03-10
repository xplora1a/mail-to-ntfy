<?php

/*
Plugin Name: Mail to Ntfy
Plugin URI: https://github.com/xplora1a/mail-to-ntfy
Description: Will send a ntfy message as well as sending the email;
Version: 1.0
Author: Stuart Ward
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MAIL_TO_NTFY_VERSION', '1.0.0' );

add_filter('wp_mail','ntfy_mails', 10,1);

function ntfy_mails($args){
    $to = $args['to'];
    $subject = $args['subject'];
    $message = $args['message'];

    // send notification with WP_http.post
    $response = wp_remote_post('https://ntfy.sh/readingcyclecampaign', array(
        'headers' => array('Content-Type' => 'text/plain;'),
        'body' =>
            'email:' . $to . '\n' .
            'subject:' . $subject . '\n' .   
            'message:' . $message
        )
    );
    return $args;
}
