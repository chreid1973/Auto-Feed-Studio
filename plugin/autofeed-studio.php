<?php
/**
 * Plugin Name: AutoFeed Studio
 * Description: Unified RSS importer + full‑text extractor with a modern, friendly UI.
 * Version: 1.0.0-alpha
 * Author: AutoFeed Team
 * License: MIT
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define('AFS_OPT_KEY', 'afs_options');
define('AFS_CRON_HOOK', 'afs_cron_ingest');

add_action('admin_menu', function(){
    add_options_page('AutoFeed Studio', 'AutoFeed Studio', 'manage_options', 'afs', 'afs_render_settings');
});
add_action('admin_init', function(){
    register_setting('afs_settings', AFS_OPT_KEY);
    add_settings_section('afs_main', __('AutoFeed Studio', 'afs'), function(){
        echo '<p>Welcome to AutoFeed Studio — import feeds, extract full text, publish beautiful posts.</p>';
    }, 'afs');
    add_settings_field('afs_campaigns', __('Campaigns JSON (dev scaffold)', 'afs'), function(){
        $o = get_option(AFS_OPT_KEY, ['campaigns_json'=>'[]']);
        echo '<textarea name="'.esc_attr(AFS_OPT_KEY).'[campaigns_json]" rows="12" class="large-text code">'.esc_textarea($o['campaigns_json']).'</textarea>';
    }, 'afs', 'afs_main');
});
function afs_render_settings(){
    echo '<div class="wrap"><h1>AutoFeed Studio</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('afs_settings'); do_settings_sections('afs'); submit_button('Save');
    echo '</form></div>';
}
add_action('rest_api_init', function(){
    register_rest_route('autofeed/v1', '/extract', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'args' => ['url'=>['required'=>true]],
        'callback' => function($req){
            $url = esc_url_raw($req['url']);
            return new WP_REST_Response([ 'content' => '<p>(stub) extracted content for: '.esc_html($url).'</p>' ], 200);
        }
    ]);
    register_rest_route('autofeed/v1', '/preview', [
        'methods' => 'GET',
        'permission_callback' => function(){ return current_user_can('manage_options'); },
        'args' => ['feed'=>['required'=>true]],
        'callback' => function($req){
            if ( ! function_exists('fetch_feed') ) require_once ABSPATH . WPINC . '/feed.php';
            $feed = fetch_feed( esc_url_raw($req['feed']) );
            if (is_wp_error($feed)) return new WP_Error('feed_err', $feed->get_error_message(), ['status'=>400]);
            $items = $feed->get_items(0,5); $titles = [];
            foreach($items as $it) $titles[] = wp_strip_all_tags($it->get_title());
            return ['titles'=>$titles];
        }
    ]);
});
