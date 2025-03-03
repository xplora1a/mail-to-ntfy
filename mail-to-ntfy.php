<?php

/*
Plugin Name: Mail to Ntfy
Description: Will replace the default wp_mail function with a custom function that sends the email via ntfy.sh
Version: 1.0
Author: Stuart Ward
*/

function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
    $atts = apply_filters( 'wp_mail', compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );

    // sena notification via ntfy using curl
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,'https://nfty.sh/readingcyclecampaigne');
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query(array('to'=>$atts['to'],'subject'=>$atts['subject'],'message'=>$atts['message'])));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch);
    curl_close($ch);     

    return true;
}