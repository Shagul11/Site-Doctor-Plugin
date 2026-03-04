<?php
/*
Plugin Name: Site Doctor
Description: Monitor and improve your WordPress site health, performance, and cleanup.
Version: 1.0
Author: Sayed Shagul
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: site-doctor
*/

if ( ! defined('ABSPATH') ) {
    exit;
}

// Include the dashboard UI
require_once plugin_dir_path(__FILE__) . 'admin/dashboard.php';

/**
 * 1. CREATE THE MENU
 */
add_action('admin_menu', 'wpsd_menu');
function wpsd_menu() {
    add_menu_page(
        'WP Site Doctor',
        'Site Doctor',
        'manage_options',
        'wp-site-doctor',
        'wpsd_dashboard',
        'dashicons-heart'
    );
}

/**
 * 2. ENQUEUE ASSETS (CSS/JS) PROPERLY
 */
add_action('admin_enqueue_scripts', 'wpsd_admin_assets');
function wpsd_admin_assets($hook) {
    // Only load these files on our specific plugin page to avoid slowing down the rest of WP
    if ($hook != 'toplevel_page_wp-site-doctor') {
        return;
    }

    // Styles
    wp_enqueue_style('wpsd-admin', plugin_dir_url(__FILE__) . 'assets/css/admin.css');
    wp_enqueue_style('wpsd-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css', array(), '1.0');

    // Script
    wp_enqueue_script(
        'wpsd-admin-js',
        plugin_dir_url(__FILE__) . 'assets/js/admin.js',
        array('jquery'),
        '1.0',
        true
    );

    // SECURITY: Pass a Nonce (token) to our Javascript file
    wp_localize_script('wpsd-admin-js', 'wpsd_vars', array(
        'nonce' => wp_create_nonce('wpsd_secure_action')
    ));
}

/**
 * 3. AJAX ACTION: RUN SCAN
 */
add_action('wp_ajax_wpsd_run_scan', 'wpsd_run_scan');
function wpsd_run_scan() {
    // Check security token
    check_ajax_referer('wpsd_secure_action', 'security');

    // Check if user has permission
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    global $wpdb;

    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    $response = array(
        'plugins' => count(get_plugins()),
        'wp'      => get_bloginfo('version'),
        'theme'   => wp_get_theme()->get('Name'),
        'posts'   => wp_count_posts()->publish,
        'db'      => wpsd_database_size()
    );

    wp_send_json($response);
}

/**
 * 4. AJAX ACTION: CLEANUP
 */
add_action('wp_ajax_wpsd_cleanup', 'wpsd_cleanup');
function wpsd_cleanup() {
    check_ajax_referer('wpsd_secure_action', 'security');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    global $wpdb;
    $cleaned = 0;

    $cleaned += $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%'");
    $cleaned += $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type='revision'");
    $cleaned += $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_approved='spam'");
    $cleaned += $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status='trash'");

    wp_send_json([
        'cleaned' => $cleaned,
        'size'    => wpsd_get_cleanup_size()
    ]);
}

/**
 * 5. AJAX ACTION: FIX ISSUES
 */
add_action('wp_ajax_wpsd_fix_issues', 'wpsd_fix_issues');
function wpsd_fix_issues() {
    check_ajax_referer('wpsd_secure_action', 'security');
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    global $wpdb;
    $fixed = 0;

    $fixed += $wpdb->query("DELETE FROM $wpdb->posts WHERE post_type='revision'");
    $fixed += $wpdb->query("DELETE FROM $wpdb->comments WHERE comment_approved='spam'");
    $fixed += $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%'");
    $fixed += $wpdb->query("DELETE FROM $wpdb->posts WHERE post_status='trash'");

    wp_send_json([
        "message" => "✅ Issues fixed. Cleaned items: " . $fixed
    ]);
}

/**
 * HELPER FUNCTIONS
 */
function wpsd_plugin_count() {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    echo count(get_plugins());
}

function wpsd_database_size() {
    global $wpdb;
    $db_size = $wpdb->get_var("SELECT SUM(data_length + index_length) FROM information_schema.tables WHERE table_schema = DATABASE()");
    return size_format($db_size);
}

function wpsd_health_score() {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $score = 100;
    if (count(get_plugins()) > 20) { $score -= 10; }
    if (get_bloginfo('version') < 6) { $score -= 10; }
    if (wp_count_posts()->publish < 5) { $score -= 5; }
    return $score;
}

function wpsd_get_cleanup_size() {
    global $wpdb;
    $total = 0;

    // Revisions size
    $revisions = $wpdb->get_results("SELECT LENGTH(post_content) as size FROM $wpdb->posts WHERE post_type='revision'");
    foreach($revisions as $rev) { $total += $rev->size; }

    // Spam comments size
    $spam = $wpdb->get_results("SELECT LENGTH(comment_content) as size FROM $wpdb->comments WHERE comment_approved='spam'");
    foreach($spam as $c) { $total += $c->size; }

    // Transients size
    $transients = $wpdb->get_results("SELECT LENGTH(option_value) as size FROM $wpdb->options WHERE option_name LIKE '_transient_%'");
    foreach($transients as $t) { $total += $t->size; }

    return size_format($total);
}