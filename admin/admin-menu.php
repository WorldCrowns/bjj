<?php
/**
 * Admin Menu for BJJ Tournament Manager
 */

add_action( 'admin_menu', 'bjj_register_admin_menu' );
function bjj_register_admin_menu() {
    // Add the main menu page.
    add_menu_page(
        __( 'BJJ Tournament Manager', 'bjj' ), // Page title
        __( 'BJJ Tournament', 'bjj' ),           // Menu title
        'manage_options',                        // Capability required
        'bjj',                                   // Menu slug
        'bjj_render_admin_page',                 // Callback function for page content
        'dashicons-tickets',                     // Icon
        6                                        // Position
    );

    // Add submenu pages.
    add_submenu_page(
        'bjj',
        __( 'Competitors', 'bjj' ),
        __( 'Competitors', 'bjj' ),
        'manage_options',
        'bjj-competitors',
        'bjj_competitors_page'
    );
    
    add_submenu_page(
        'bjj',
        __( 'Academies', 'bjj' ),
        __( 'Academies', 'bjj' ),
        'manage_options',
        'bjj-academies',
        'bjj_academies_page'
    );
    
    add_submenu_page(
        'bjj',
        __( 'Weights', 'bjj' ),
        __( 'Weights', 'bjj' ),
        'manage_options',
        'bjj-weights',
        'bjj_weights_page'
    );

    add_submenu_page(
        'bjj',
        __( 'Order of Fights', 'bjj' ),
        __( 'Order of Fights', 'bjj' ),
        'manage_options',
        'bjj-order-of-fights',
        'bjj_order_of_fights_page'
    );

    add_submenu_page(
        'bjj',
        __( 'Bracket', 'bjj' ),
        __( 'Bracket', 'bjj' ),
        'manage_options',
        'bjj-bracket',
        'bjj_bracket_page'
    );

    add_submenu_page(
        'bjj',
        __( 'Live', 'bjj' ),
        __( 'Live', 'bjj' ),
        'manage_options',
        'bjj-live',
        'bjj_live_page'
    );

    add_submenu_page(
        'bjj',
        __( 'Result', 'bjj' ),
        __( 'Result', 'bjj' ),
        'manage_options',
        'bjj-result',
        'bjj_result_page'
    );
}

// Main menu page callback (redirect to Competitors page by default).
function bjj_render_admin_page() {
    bjj_competitors_page();
}

// Callback functions for each submenu.
function bjj_competitors_page() {
    include_once BJJ_PLUGIN_DIR . 'admin/competitors-tab.php';
}
function bjj_academies_page() {
    include_once BJJ_PLUGIN_DIR . 'admin/academies-tab.php';
}
function bjj_weights_page() {
    include_once BJJ_PLUGIN_DIR . 'admin/weights-tab.php';
}
function bjj_order_of_fights_page() {
    include_once BJJ_PLUGIN_DIR . 'admin/order-of-fights-tab.php';
}
function bjj_bracket_page() {
    include_once BJJ_PLUGIN_DIR . 'admin/bracket-tab.php';
}
function bjj_live_page() {
    include_once BJJ_PLUGIN_DIR . 'admin/live-tab.php';
}
function bjj_result_page() {
    include_once BJJ_PLUGIN_DIR . 'admin/result-tab.php';
}
