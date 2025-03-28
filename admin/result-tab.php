<?php
/**
 * Result Tab (Admin)
 * Allows you to select the winner (by competitor) for each match and saves the results.
 */

// Process match result form submission.
if ( isset( $_POST['bjj_result_nonce'] ) && wp_verify_nonce( $_POST['bjj_result_nonce'], 'bjj_save_results' ) ) {
    $results = array();
    if ( ! empty( $_POST['winner'] ) && is_array( $_POST['winner'] ) ) {
        foreach ( $_POST['winner'] as $match_id => $competitor_id ) {
            $match_id      = sanitize_text_field( $match_id );
            $competitor_id = intval( $competitor_id );
            if ( $competitor_id > 0 ) {
                $results[ $match_id ] = $competitor_id;
            }
        }
    }
    update_option( 'bjj_match_results', $results );
    echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Match results saved successfully!', 'bjj' ) . '</p></div>';
}

// Retrieve competitor data.
$competitors = get_option( 'bjj_competitors', array() );

// For demonstration, define matches manually.
// (In practice, you might generate these from competitor entries.)
$matches = array(
    array(
        'id'            => 'match1',
        'competitor_a'  => key( $competitors ),
        'competitor_b'  => next( array_keys( $competitors ) ),
    ),
    // Add additional matches as needed.
);

$saved_results = get_option( 'bjj_match_results', array() );
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Enter Results', 'bjj' ); ?></h2>
    <p><?php _e( 'Select the winner for each match.', 'bjj' ); ?></p>

    <?php if ( empty( $matches ) ) : ?>
        <p><?php _e( 'No matches available. Please create matches manually.', 'bjj' ); ?></p>
    <?php else : ?>
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
                    
                    $comp_a_name = isset( $competitors[ $comp_a_id ] )
                        ? $competitors[ $comp_a_id ]['first_name'] . ' ' . $competitors[ $comp_a_id ]['last_name']
                        : __( 'Unknown', 'bjj' );
                    $comp_b_name = isset( $competitors[ $comp_b_id ] )
                        ? $competitors[ $comp_b_id ]['first_name'] . ' ' . $competitors[ $comp_b_id ]['last_name']
                        : __( 'Unknown', 'bjj' );
                    
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
    <?php endif; ?>

    <div class="academy-wins">
        <h3><?php _e( 'Academy Wins Count', 'bjj' ); ?></h3>
        <p><?php _e( 'Display which academy/school has the most wins.', 'bjj' ); ?></p>
        <!-- Placeholder: You can add logic here to aggregate and display win counts per academy -->
        <ul>
            <li>Alpha Academy: 3 wins</li>
            <li>Beta School: 2 wins</li>
        </ul>
    </div>
</div>
