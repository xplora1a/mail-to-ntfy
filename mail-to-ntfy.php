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

define( 'MAIL_TO_NTFY_CHANNEL_OPTION', 'mail_to_ntfy_channel' );

add_filter('wp_mail','ntfy_mails', 10,1);
add_action( 'admin_init', 'mail_to_ntfy_register_settings' );
add_action( 'admin_menu', 'mail_to_ntfy_add_settings_page' );

function mail_to_ntfy_register_settings() {
    register_setting(
        'mail_to_ntfy_settings',
        MAIL_TO_NTFY_CHANNEL_OPTION,
        array(
            'type' => 'string',
            'sanitize_callback' => 'mail_to_ntfy_sanitize_channel',
            'default' => 'readingcyclecampaign',
        )
    );

    add_settings_section(
        'mail_to_ntfy_settings_section',
        'ntfy settings',
        '__return_false',
        'mail_to_ntfy'
    );

    add_settings_field(
        MAIL_TO_NTFY_CHANNEL_OPTION,
        'Channel',
        'mail_to_ntfy_channel_field',
        'mail_to_ntfy',
        'mail_to_ntfy_settings_section'
    );
}

function mail_to_ntfy_add_settings_page() {
    add_options_page(
        'Mail to Ntfy',
        'Mail to Ntfy',
        'manage_options',
        'mail_to_ntfy',
        'mail_to_ntfy_settings_page'
    );
}

function mail_to_ntfy_sanitize_channel( $channel ) {
    return sanitize_text_field( trim( $channel ) );
}

function mail_to_ntfy_channel_field() {
    $channel = get_option( MAIL_TO_NTFY_CHANNEL_OPTION, '' );
    ?>
    <input type="text" name="<?php echo esc_attr( MAIL_TO_NTFY_CHANNEL_OPTION ); ?>" value="<?php echo esc_attr( $channel ); ?>" class="regular-text" required />
    <p class="description">The ntfy channel is the topic where notifications will be published. Subscribe to the same channel in the ntfy app or at ntfy.sh to receive these email notifications.</p>
    <?php
}

function mail_to_ntfy_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>Mail to Ntfy</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'mail_to_ntfy_settings' );
            do_settings_sections( 'mail_to_ntfy' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function ntfy_mails($args){
    $to = $args['to'];
    $subject = $args['subject'];
    $message = preg_split("/<!--[ a-z]+-->/",$args['message'])[2];
    $message = preg_replace("#</*strong>#", " ", $message);
    $message = strip_tags($message);

    // send notification with wp_remote_post
    $channel = get_option( MAIL_TO_NTFY_CHANNEL_OPTION, '' );
    if (empty($channel)) {
        return $args; // If no channel is set, do not send notification
    }
    $response = wp_remote_post('https://ntfy.sh/' . rawurlencode( $channel ), array(
        'headers' => array('Content-Type' => 'text/plain;',
            'Title' => $subject),
        'body' => $message
        )
    );
    return $args;
}
