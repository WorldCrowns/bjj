weight-tab
<?php
/**
 * Weights Tab (Admin)
 * Allows the admin to manage weight options (in pounds) for competitors.
 */

// Process form submission to update weight options.
if ( isset( $_POST['bjj_weight_options_nonce'] ) && wp_verify_nonce( $_POST['bjj_weight_options_nonce'], 'bjj_save_weight_options' ) ) {
    $weights_raw = isset( $_POST['weights'] ) ? sanitize_text_field( $_POST['weights'] ) : '';
    // Expecting comma-separated values.
    $weights_array = array_filter( array_map( 'trim', explode( ',', $weights_raw ) ) );
    update_option( 'bjj_weight_options', $weights_array );
    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Weight options updated successfully!', 'bjj' ) . '</p></div>';
}

// Retrieve current weight options.
$current_weights = get_option( 'bjj_weight_options', array() );
$weights_string = implode( ', ', $current_weights );
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Manage Weight Options', 'bjj' ); ?></h2>
    <p><?php _e( 'Enter weight values in pounds, separated by commas. These options will appear in the competitor form.', 'bjj' ); ?></p>
    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_weight_options', 'bjj_weight_options_nonce' ); ?>
        <table class="form-table">
            <tr>
                <th><label for="weights"><?php _e( 'Weight Options (lbs)', 'bjj' ); ?></label></th>
                <td>
                    <input type="text" name="weights" id="weights" value="<?php echo esc_attr( $weights_string ); ?>" style="width: 100%;">
                    <p class="description"><?php _e( 'Example: 100, 120, 140, 160, 180', 'bjj' ); ?></p>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Weight Options', 'bjj' ); ?>">
        </p>
    </form>
</div>
