<?php
/**
 * Plugin Name: Tournament Manager
 * Description: Organizes competitor data from Tickera, sets up match assignments, tournament brackets, live displays, and results.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: tournament-manager
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin path and URL.
define( 'TM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the admin menu for backend functionality.
if ( is_admin() ) {
    require_once TM_PLUGIN_DIR . 'admin/admin-menu.php';
}

// Enqueue admin styles and scripts.
function tm_enqueue_admin_scripts( $hook ) {
    // Only load our scripts on our plugin page.
    if ( strpos( $hook, 'tournament-manager' ) === false ) {
        return;
    }
    wp_enqueue_style( 'tm-admin-style', TM_PLUGIN_URL . 'assets/css/admin-style.css' );
    wp_enqueue_script( 'jquery-ui-sortable' ); // For drag and drop.
    wp_enqueue_script( 'tm-admin-scripts', TM_PLUGIN_URL . 'assets/js/admin-scripts.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0', true );

    // Pass AJAX URL to our script.
    wp_localize_script( 'tm-admin-scripts', 'tm_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'tm_enqueue_admin_scripts' );

/**
 * Example AJAX handler for live tab updates.
 */
function tm_update_live_data() {
    // Prepare live match data.
    $response = array(
        'status' => 'success',
        'data'   => 'This is live match data update'
    );
    wp_send_json( $response );
}
add_action( 'wp_ajax_tm_update_live_data', 'tm_update_live_data' );

/* -----------------------------------------------
   Shortcode Registrations
-------------------------------------------------*/

/**
 * Competitors Shortcode: [tm_competitors]
 */
function tm_competitors_shortcode() {
    ob_start();
    include TM_PLUGIN_DIR . 'shortcodes/competitors-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'tm_competitors', 'tm_competitors_shortcode' );

/**
 * Order of Fights Shortcode: [tm_order_of_fights]
 */
function tm_order_of_fights_shortcode() {
    ob_start();
    include TM_PLUGIN_DIR . 'shortcodes/order-of-fights-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'tm_order_of_fights', 'tm_order_of_fights_shortcode' );

/**
 * Bracket Shortcode: [tm_bracket]
 */
function tm_bracket_shortcode() {
    ob_start();
    include TM_PLUGIN_DIR . 'shortcodes/bracket-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'tm_bracket', 'tm_bracket_shortcode' );

/**
 * Live Queue Shortcode: [tm_live]
 */
function tm_live_shortcode() {
    ob_start();
    include TM_PLUGIN_DIR . 'shortcodes/live-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'tm_live', 'tm_live_shortcode' );

/**
 * Result Shortcode: [tm_result]
 */
function tm_result_shortcode() {
    ob_start();
    include TM_PLUGIN_DIR . 'shortcodes/result-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'tm_result', 'tm_result_shortcode' );
