admin-menu.php
<?php
// Create main admin page for Tournament Manager.
add_action( 'admin_menu', 'tm_register_menu' );

function tm_register_menu() {
    add_menu_page(
        __( 'Tournament Manager', 'tournament-manager' ),
        __( 'Tournament Manager', 'tournament-manager' ),
        'manage_options',
        'tournament-manager',
        'tm_render_admin_page',
        'dashicons-tickets', // Change the icon as needed.
        6
    );
}

function tm_render_admin_page() {
    // Determine which tab is active.
    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'competitors';
    ?>
    <div class="wrap">
        <h1><?php _e( 'Tournament Manager', 'tournament-manager' ); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=tournament-manager&tab=competitors" class="nav-tab <?php echo $active_tab == 'competitors' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Competitors', 'tournament-manager' ); ?></a>
            <a href="?page=tournament-manager&tab=order-of-fights" class="nav-tab <?php echo $active_tab == 'order-of-fights' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Order of Fights', 'tournament-manager' ); ?></a>
            <a href="?page=tournament-manager&tab=bracket" class="nav-tab <?php echo $active_tab == 'bracket' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Bracket', 'tournament-manager' ); ?></a>
            <a href="?page=tournament-manager&tab=live" class="nav-tab <?php echo $active_tab == 'live' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Live', 'tournament-manager' ); ?></a>
            <a href="?page=tournament-manager&tab=result" class="nav-tab <?php echo $active_tab == 'result' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Result', 'tournament-manager' ); ?></a>
        </h2>
        <div id="tm-tab-content">
            <?php
            // Include the corresponding tab file.
            switch ( $active_tab ) {
                case 'order-of-fights':
                    include TM_PLUGIN_DIR . 'admin/order-of-fights-tab.php';
                    break;
                case 'bracket':
                    include TM_PLUGIN_DIR . 'admin/bracket-tab.php';
                    break;
                case 'live':
                    include TM_PLUGIN_DIR . 'admin/live-tab.php';
                    break;
                case 'result':
                    include TM_PLUGIN_DIR . 'admin/result-tab.php';
                    break;
                case 'competitors':
                default:
                    include TM_PLUGIN_DIR . 'admin/competitors-tab.php';
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}
