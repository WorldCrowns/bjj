<?php
/**
 * Plugin Name: BJJ Tournament Plugin
 * Description: A plugin for managing BJJ tournaments with events, categories, weight classes, academies, competitors, mats, matchmaking, brackets, schedule matches, results, reset, and a media library uploader for images.
 * Version: 1.4
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// If the SSE query parameter is set, load the SSE endpoint and exit.
if ( isset($_GET['bjj_tournament_sse']) && $_GET['bjj_tournament_sse'] == 1 ) {
    require_once plugin_dir_path(__FILE__) . 'includes/bjj-tournament-sse.php';
    exit;
}

// Define plugin constants
define('BJJ_TOURNAMENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BJJ_TOURNAMENT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once BJJ_TOURNAMENT_PLUGIN_DIR . 'includes/bjj-tournament-functions.php';
require_once BJJ_TOURNAMENT_PLUGIN_DIR . 'includes/class-bjj-tournament-admin.php';
require_once BJJ_TOURNAMENT_PLUGIN_DIR . 'includes/class-bjj-tournament-shortcodes.php';

// Plugin activation hook to create database tables
register_activation_hook(__FILE__, 'bjj_tournament_create_tables');

// Initialize Admin if in backend
if ( is_admin() ) {
    $bjjTournamentAdmin = new BJTTournamentAdmin();
}

// Initialize Shortcodes
$bjjTournamentShortcodes = new BJTTournamentShortcodes();

/**
 * Enqueue front-end scripts and styles
 */
function bjj_tournament_enqueue_scripts() {
    wp_enqueue_style('bjj-tournament-style', BJJ_TOURNAMENT_PLUGIN_URL . 'assets/css/bjj-tournament-style.css');
    
    // Enqueue flatpickr (if needed for other features) or use native inputs
    // For our current setup, we're using native datetime-local inputs.
    
    wp_enqueue_script('bjj-tournament-script', BJJ_TOURNAMENT_PLUGIN_URL . 'assets/js/bjj-tournament-script.js', array('jquery', 'jquery-ui-sortable'), '1.4', true);
    
    wp_localize_script('bjj-tournament-script', 'bjjTournament', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('bjj_tournament_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'bjj_tournament_enqueue_scripts');

/**
 * Enqueue admin scripts and styles, including WP Media Library and our custom admin JS
 */
function bjj_tournament_admin_enqueue_scripts($hook) {
    if (strpos($hook, 'bjj-tournament') === false) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('bjj-tournament-admin-script', BJJ_TOURNAMENT_PLUGIN_URL . 'assets/js/bjj-tournament-admin.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'bjj_tournament_admin_enqueue_scripts');
