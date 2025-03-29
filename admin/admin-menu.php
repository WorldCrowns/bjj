<?php
/**
 * Admin Menu for BJJ Tournament Manager
 */

add_action( 'admin_menu', 'bjj_register_admin_menu' );
function bjj_register_admin_menu() {
    // Main menu page.
    add_menu_page(
        __( 'BJJ Tournament Manager', 'bjj' ),
        __( 'BJJ Tournament', 'bjj' ),
        'manage_options',
        'bjj',
        'bjj_render_admin_page',
        'dashicons-tickets',
        6
    );

    // Submenu pages.
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

function bjj_render_admin_page() {
    // Determine active tab from query parameter; default to Competitors.
    $current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'competitors';
    ?>
    <div class="wrap">
        <h1><?php _e( 'BJJ Tournament Manager', 'bjj' ); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=bjj&tab=competitors" class="nav-tab <?php echo ( $current_tab == 'competitors' ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Competitors', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=academies" class="nav-tab <?php echo ( $current_tab == 'academies' ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Academies', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=weights" class="nav-tab <?php echo ( $current_tab == 'weights' ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Weights', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=order-of-fights" class="nav-tab <?php echo ( $current_tab == 'order-of-fights' ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Order of Fights', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=bracket" class="nav-tab <?php echo ( $current_tab == 'bracket' ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Bracket', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=live" class="nav-tab <?php echo ( $current_tab == 'live' ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Live', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=result" class="nav-tab <?php echo ( $current_tab == 'result' ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Result', 'bjj' ); ?></a>
        </h2>
        <div class="bjj-admin-content">
            <?php
            switch ( $current_tab ) {
                case 'academies':
                    bjj_academies_page();
                    break;
                case 'weights':
                    bjj_weights_page();
                    break;
                case 'order-of-fights':
                    bjj_order_of_fights_page();
                    break;
                case 'bracket':
                    bjj_bracket_page();
                    break;
                case 'live':
                    bjj_live_page();
                    break;
                case 'result':
                    bjj_result_page();
                    break;
                case 'competitors':
                default:
                    bjj_competitors_page();
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

// Callback functions to include corresponding files.
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
