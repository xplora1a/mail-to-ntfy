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

    // send a notification via ntfy using curl
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,'https://ntfy.sh/readingcyclecampaigne');
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query('to:'+$to+' subject:'+$subject+' message:'+$message));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch);
    curl_close($ch);     

    return $args;
}
