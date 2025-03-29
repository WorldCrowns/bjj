<?php
/**
 * Weights Tab (Admin) - CRUD functionality for weight options.
 */

// Process form submission to update weight options.
if ( isset( $_POST['bjj_weight_options_nonce'] ) && wp_verify_nonce( $_POST['bjj_weight_options_nonce'], 'bjj_save_weight_options' ) ) {
    // Use textarea input: one weight per line.
    $weights_raw = isset( $_POST['weights'] ) ? wp_unslash( $_POST['weights'] ) : '';
    $weights_array = array_filter( array_map( 'trim', explode( "\n", $weights_raw ) ) );
    $sanitized_weights = array();
    foreach ( $weights_array as $w ) {
        $sanitized_weights[] = sanitize_text_field( $w );
    }
    update_option( 'bjj_weight_options', $sanitized_weights );
    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Weight options updated successfully!', 'bjj' ) . '</p></div>';
}

// Process deletion of a single weight option.
if ( isset($_GET['action']) && $_GET['action'] === 'delete_weight' && isset($_GET['index']) ) {
    $index = intval($_GET['index']);
    $weights = get_option( 'bjj_weight_options', array() );
    if ( isset($weights[$index]) && isset($_GET['nonce']) && wp_verify_nonce($_GET['nonce'], 'delete_weight_'.$index) ) {
        unset($weights[$index]);
        update_option( 'bjj_weight_options', $weights );
        echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Weight option deleted successfully!', 'bjj' ) . '</p></div>';
    }
}

// Retrieve current weight options.
$current_weights = get_option( 'bjj_weight_options', array() );
$weights_string = implode( "\n", $current_weights );
?>
<div class="bjj-tab-content">
    <h2><?php _e( 'Manage Weight Options', 'bjj' ); ?></h2>
    <p><?php _e( 'Enter one weight value per line (in pounds). Example:', 'bjj' ); ?></p>
    <pre>100
120
140
160
180</pre>
    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_weight_options', 'bjj_weight_options_nonce' ); ?>
        <table class="form-table">
            <tr>
                <th><label for="weights"><?php _e( 'Weight Options (lbs)', 'bjj' ); ?></label></th>
                <td>
                    <textarea name="weights" id="weights" rows="5" style="width: 100%;"><?php echo esc_textarea( $weights_string ); ?></textarea>
                    <p class="description"><?php _e( 'Enter one weight value per line. Example: 100, 120, etc.', 'bjj' ); ?></p>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Weight Options', 'bjj' ); ?>">
        </p>
    </form>

    <hr>
    <h3><?php _e( 'Current Weight Options', 'bjj' ); ?></h3>
    <?php if ( ! empty( $current_weights ) ) : ?>
        <ul>
            <?php foreach ( $current_weights as $index => $w ) : ?>
                <li>
                    <?php echo esc_html( $w ); ?>
                    (<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'delete_weight', 'index' => $index, 'nonce' => wp_create_nonce('delete_weight_'.$index) ), menu_page_url( 'bjj-weights', false ) ) ); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this weight option?','bjj'); ?>');"><?php _e('Delete','bjj'); ?></a>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p><?php _e( 'No weight options found.', 'bjj' ); ?></p>
    <?php endif; ?>
</div>
