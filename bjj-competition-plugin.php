<?php
/**
 * Plugin Name: BJJ Competition Plugin
 * Plugin URI:  https://example.com
 * Description: Manage local BJJ competitions (categories, academies, competitors, mats, matches, results, podium).
 * Version:     1.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * License:     GPL2
 * Text Domain: bjj-competition
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define constants
define( 'BJJ_COMPETITION_PLUGIN_VERSION', '1.0' );
define( 'BJJ_COMPETITION_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BJJ_COMPETITION_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once BJJ_COMPETITION_PLUGIN_DIR . 'includes/class-bjj-competition-activator.php';
require_once BJJ_COMPETITION_PLUGIN_DIR . 'includes/class-bjj-competition-deactivator.php';
require_once BJJ_COMPETITION_PLUGIN_DIR . 'includes/class-bjj-competition-admin.php';
require_once BJJ_COMPETITION_PLUGIN_DIR . 'includes/class-bjj-competition-shortcodes.php';
require_once BJJ_COMPETITION_PLUGIN_DIR . 'includes/class-bjj-competition-ajax.php';

/**
 * Activation/Deactivation Hooks
 */
register_activation_hook( __FILE__, array( 'BJJ_Competition_Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'BJJ_Competition_Deactivator', 'deactivate' ) );

/**
 * Main plugin initialization
 */
function bjj_competition_plugin_init() {
    // Load textdomain if needed
    load_plugin_textdomain( 'bjj-competition', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    // Initialize admin menu/pages
    new BJJ_Competition_Admin();

    // Initialize shortcodes
    new BJJ_Competition_Shortcodes();

    // Initialize AJAX handlers
    new BJJ_Competition_AJAX();
}
add_action( 'plugins_loaded', 'bjj_competition_plugin_init' );

/**
 * Enqueue scripts and styles
 */
function bjj_competition_plugin_enqueue_scripts() {
    // Enqueue WP Media scripts in admin
    if ( is_admin() ) {
        wp_enqueue_media();
    }
    
    // CSS
    wp_enqueue_style(
        'bjj-competition-style',
        BJJ_COMPETITION_PLUGIN_URL . 'assets/css/bjj-competition-style.css',
        array(),
        BJJ_COMPETITION_PLUGIN_VERSION
    );

    // JS
    wp_enqueue_script(
        'bjj-competition-script',
        BJJ_COMPETITION_PLUGIN_URL . 'assets/js/bjj-competition-script.js',
        array( 'jquery' ),
        BJJ_COMPETITION_PLUGIN_VERSION,
        true
    );

    // Localize for AJAX
    wp_localize_script( 'bjj-competition-script', 'bjjCompetitionAjax', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'bjj_competition_nonce' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'bjj_competition_plugin_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'bjj_competition_plugin_enqueue_scripts' );
