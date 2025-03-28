<?php
// Create the main admin page for BJJ Tournament Manager.
add_action( 'admin_menu', 'bjj_register_menu' );

function bjj_register_menu() {
    add_menu_page(
        __( 'BJJ Tournament Manager', 'bjj' ),
        __( 'BJJ Tournament', 'bjj' ),
        'manage_options',
        'bjj',
        'bjj_render_admin_page',
        'dashicons-tickets',
        6
    );
}

function bjj_render_admin_page() {
    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'competitors';
    ?>
    <div class="wrap">
        <h1><?php _e( 'BJJ Tournament Manager', 'bjj' ); ?></h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=bjj&tab=competitors" class="nav-tab <?php echo $active_tab == 'competitors' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Competitors', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=order-of-fights" class="nav-tab <?php echo $active_tab == 'order-of-fights' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Order of Fights', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=bracket" class="nav-tab <?php echo $active_tab == 'bracket' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Bracket', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=live" class="nav-tab <?php echo $active_tab == 'live' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Live', 'bjj' ); ?></a>
            <a href="?page=bjj&tab=result" class="nav-tab <?php echo $active_tab == 'result' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Result', 'bjj' ); ?></a>
        </h2>
        <div id="bjj-tab-content">
            <?php
            switch ( $active_tab ) {
                case 'order-of-fights':
                    include BJJ_PLUGIN_DIR . 'admin/order-of-fights-tab.php';
                    break;
                case 'bracket':
                    include BJJ_PLUGIN_DIR . 'admin/bracket-tab.php';
                    break;
                case 'live':
                    include BJJ_PLUGIN_DIR . 'admin/live-tab.php';
                    break;
                case 'result':
                    include BJJ_PLUGIN_DIR . 'admin/result-tab.php';
                    break;
                case 'competitors':
                default:
                    include BJJ_PLUGIN_DIR . 'admin/competitors-tab.php';
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}
