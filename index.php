<?php
/**
 * Aeeiee S3 Plugin
 *
 * @author              Aeeiee Inc.
 *
 * @wordpress-plugin
 * Plugin Name:        Aeeiee S3 Plugin
 * Description:        This plugin allows us upload files to an Amazon S3 bucket.
 * Author:            Aeeiee Inc.
 * Author URI:        https://www.aeeiee.com
 * Version:            1.0
 * Requires PHP:     7.2
 */

add_action('admin_enqueue_scripts', function () {
    // loads the AWS SDK
    wp_enqueue_script('aeeiee-aws-sdk', 'https://sdk.amazonaws.com/js/aws-sdk-2.828.0.min.js');

});

add_action('admin_menu', 'aeeiee_s3_uploader');

function aeeiee_s3_uploader()
{
    // add a new page to the menu
    add_menu_page('Aeeiee S3 Page', 'Aeeiee S3 Uploader', 'manage_options', 'aeeiee-s3-uploader', 'aeeiee_s3_callback', 'dashicons-chart-pie');

    // enqueue the JS scripts in the admin page
    add_action('admin_enqueue_scripts', 'aeeiee_s3_enqueue_scripts');
}

function aeeiee_s3_enqueue_scripts($hook_suffix)
{
    // if on the uploader page in the admin section, load the JS file
    if ($hook_suffix === 'toplevel_page_aeeiee-s3-uploader') {
        wp_enqueue_script('aeeiee-js', plugins_url('/aeeiee-s3.js', __FILE__));

        $aws_vars = array(
            'accessKeyId' => "YOUR ACCESS KEY ID",
            'secretAccessKey' => "YOUR SECRET ACCESS KEY",
        );

        // pass AWS Keys from the server to the client
        wp_localize_script('aeeiee-js', 'aws_vars', $aws_vars);
    }

}

function aeeiee_s3_callback()
{
    include_once plugin_dir_path(__FILE__) . '/aeeiee-s3-views.php';
}
