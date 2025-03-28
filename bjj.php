bjj.php
<?php
/**
 * Plugin Name: BJJ Tournament Manager
 * Description: Organizes competitor data from Tickera, sets up match assignments, tournament brackets, live displays, and results.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: bjj
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin path and URL.
define( 'BJJ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BJJ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the admin menu for backend functionality.
if ( is_admin() ) {
    require_once BJJ_PLUGIN_DIR . 'admin/admin-menu.php';
}

// Enqueue admin styles and scripts.
function bjj_enqueue_admin_scripts( $hook ) {
    // Only load our scripts on our plugin page.
    if ( strpos( $hook, 'bjj' ) === false ) {
        return;
    }
    wp_enqueue_style( 'bjj-admin-style', BJJ_PLUGIN_URL . 'assets/css/admin-style.css' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'bjj-admin-scripts', BJJ_PLUGIN_URL . 'assets/js/admin-scripts.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0', true );

    wp_localize_script( 'bjj-admin-scripts', 'bjj_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'bjj_enqueue_admin_scripts' );

/**
 * AJAX handler for live tab updates.
 */
function bjj_update_live_data() {
    // Prepare live match data.
    $response = array(
        'status' => 'success',
        'data'   => 'This is live match data update'
    );
    wp_send_json( $response );
}
add_action( 'wp_ajax_bjj_update_live_data', 'bjj_update_live_data' );

/* -----------------------------------------------
   Shortcode Registrations
-------------------------------------------------*/

/**
 * Competitors Shortcode: [bjj_competitors]
 */
function bjj_competitors_shortcode() {
    ob_start();
    include BJJ_PLUGIN_DIR . 'shortcodes/competitors-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'bjj_competitors', 'bjj_competitors_shortcode' );

/**
 * Order of Fights Shortcode: [bjj_order_of_fights]
 */
function bjj_order_of_fights_shortcode() {
    ob_start();
    include BJJ_PLUGIN_DIR . 'shortcodes/order-of-fights-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'bjj_order_of_fights', 'bjj_order_of_fights_shortcode' );

/**
 * Bracket Shortcode: [bjj_bracket]
 */
function bjj_bracket_shortcode() {
    ob_start();
    include BJJ_PLUGIN_DIR . 'shortcodes/bracket-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'bjj_bracket', 'bjj_bracket_shortcode' );

/**
 * Live Queue Shortcode: [bjj_live]
 */
function bjj_live_shortcode() {
    ob_start();
    include BJJ_PLUGIN_DIR . 'shortcodes/live-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'bjj_live', 'bjj_live_shortcode' );

/**
 * Result Shortcode: [bjj_result]
 */
function bjj_result_shortcode() {
    ob_start();
    include BJJ_PLUGIN_DIR . 'shortcodes/result-shortcode.php';
    return ob_get_clean();
}
add_shortcode( 'bjj_result', 'bjj_result_shortcode' );
