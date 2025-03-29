<?php
/**
 * Weights Tab (Admin)
 * Allows the admin to manage weight options (in pounds) for competitors.
 */

// Process form submission to update weight options.
if ( isset( $_POST['bjj_weight_options_nonce'] ) && wp_verify_nonce( $_POST['bjj_weight_options_nonce'], 'bjj_save_weight_options' ) ) {
    // Grab raw input from the form.
    $weights_raw = isset( $_POST['weights'] ) ? wp_unslash( $_POST['weights'] ) : '';

    // Option A: Expecting comma-separated values
    // $weights_array = array_filter( array_map( 'trim', explode( ',', $weights_raw ) ) );

    // Option B: Expect one weight per line (easier for multiple lines)
    // If you'd rather allow line-by-line input, uncomment below and comment out the comma approach:
    $weights_array = array_filter( array_map( 'trim', explode( "\n", $weights_raw ) ) );

    // Sanitize each weight as a simple text field.
    $sanitized_weights = array();
    foreach ( $weights_array as $w ) {
        $sanitized_weights[] = sanitize_text_field( $w );
    }

    // Save to the DB.
    update_option( 'bjj_weight_options', $sanitized_weights );

    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Weight options updated successfully!', 'bjj' ) . '</p></div>';
}

// Retrieve current weight options.
$current_weights = get_option( 'bjj_weight_options', array() );

// Display them in a comma- or line-separated string.
$weights_string = implode( "\n", $current_weights );
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Manage Weight Options', 'bjj' ); ?></h2>
    <p><?php _e( 'Enter weight values in pounds. Each line represents a single weight option. For example:', 'bjj' ); ?></p>
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
                    <!-- Use a textarea so you can add multiple lines. -->
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
            <?php foreach ( $current_weights as $w ) : ?>
                <li><?php echo esc_html( $w ); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p><?php _e( 'No weight options found.', 'bjj' ); ?></p>
    <?php endif; ?>
</div>
