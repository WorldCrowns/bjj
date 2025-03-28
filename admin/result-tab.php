<?php
/**
 * Result Tab (Admin)
 * Allows you to select which competitor won each match, then saves the results.
 */

// Handle form submission for match results.
if ( isset( $_POST['bjj_result_nonce'] ) && wp_verify_nonce( $_POST['bjj_result_nonce'], 'bjj_save_results' ) ) {
    // Example approach: store results in an option (array of match_id => winner_id).
    $results = array();
    if ( ! empty( $_POST['winner'] ) && is_array( $_POST['winner'] ) ) {
        foreach ( $_POST['winner'] as $match_id => $competitor_id ) {
            $match_id      = sanitize_text_field( $match_id );
            $competitor_id = intval( $competitor_id );
            // If competitor_id is 0 or empty, it means no selection was made.
            if ( $competitor_id > 0 ) {
                $results[ $match_id ] = $competitor_id;
            }
        }
    }
    update_option( 'bjj_match_results', $results );
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'Match results saved successfully!', 'bjj' ); ?></p>
    </div>
    <?php
}

// Example competitor data. In real usage, you'd fetch this from your database.
$competitors = array(
    1 => array( 'first_name' => 'John', 'last_name' => 'Doe' ),
    2 => array( 'first_name' => 'Jane', 'last_name' => 'Smith' ),
    3 => array( 'first_name' => 'Bob',  'last_name' => 'Brown' ),
    4 => array( 'first_name' => 'Alice','last_name' => 'White' ),
);

// Example matches. Each match references competitor IDs.
$matches = array(
    array(
        'id' => 'match1',
        'competitor_a' => 1,
        'competitor_b' => 2,
    ),
    array(
        'id' => 'match2',
        'competitor_a' => 3,
        'competitor_b' => 4,
    ),
);

// Get previously saved results (if any).
$saved_results = get_option( 'bjj_match_results', array() );
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Enter Results', 'bjj' ); ?></h2>
    <p><?php _e( 'Select the winner for each match.', 'bjj' ); ?></p>

    <form method="post" action="">
        <?php wp_nonce_field( 'bjj_save_results', 'bjj_result_nonce' ); ?>

        <table class="form-table">
            <thead>
                <tr>
                    <th><?php _e( 'Match ID', 'bjj' ); ?></th>
                    <th><?php _e( 'Competitor A', 'bjj' ); ?></th>
                    <th><?php _e( 'Competitor B', 'bjj' ); ?></th>
                    <th><?php _e( 'Winner', 'bjj' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $matches as $match ) : 
                    $match_id = $match['id'];
                    $comp_a_id = $match['competitor_a'];
                    $comp_b_id = $match['competitor_b'];

                    // Display competitor names
                    $comp_a_name = isset( $competitors[ $comp_a_id ] )
                        ? $competitors[ $comp_a_id ]['first_name'] . ' ' . $competitors[ $comp_a_id ]['last_name']
                        : __( 'Unknown', 'bjj' );
                    $comp_b_name = isset( $competitors[ $comp_b_id ] )
                        ? $competitors[ $comp_b_id ]['first_name'] . ' ' . $competitors[ $comp_b_id ]['last_name']
                        : __( 'Unknown', 'bjj' );

                    // Get saved winner (if any).
                    $saved_winner = isset( $saved_results[ $match_id ] ) ? $saved_results[ $match_id ] : '';
                ?>
                <tr>
                    <td><?php echo esc_html( $match_id ); ?></td>
                    <td><?php echo esc_html( $comp_a_name ); ?></td>
                    <td><?php echo esc_html( $comp_b_name ); ?></td>
                    <td>
                        <select name="winner[<?php echo esc_attr( $match_id ); ?>]">
                            <option value="0"><?php _e( 'Select winner', 'bjj' ); ?></option>
                            <option value="<?php echo intval( $comp_a_id ); ?>" <?php selected( $saved_winner, $comp_a_id ); ?>>
                                <?php echo esc_html( $comp_a_name ); ?>
                            </option>
                            <option value="<?php echo intval( $comp_b_id ); ?>" <?php selected( $saved_winner, $comp_b_id ); ?>>
                                <?php echo esc_html( $comp_b_name ); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p>
            <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Results', 'bjj' ); ?>">
        </p>
    </form>

    <div class="academy-wins">
        <h3><?php _e( 'Academy Wins Count', 'bjj' ); ?></h3>
        <p><?php _e( 'Display which academy/school has the most wins.', 'bjj' ); ?></p>
        <!-- Placeholder: This is where you could show aggregated results from $saved_results. -->
        <ul>
            <li>Alpha Academy: 3 wins</li>
            <li>Beta School: 2 wins</li>
        </ul>
    </div>
</div>
