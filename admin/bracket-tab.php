<?php
/**
 * Bracket Tab (Admin) - Dynamic Version
 * Displays a tournament bracket that expands dynamically based on competitor entries.
 */

// Retrieve competitor data and match results.
$competitors   = get_option( 'bjj_competitors', array() );
$match_results = get_option( 'bjj_match_results', array() );

// Generate the dynamic bracket structure based on competitors.
$bracket_structure = bjj_generate_bracket_structure( $competitors );

/**
 * Helper function: Get competitor name by ID.
 *
 * @param int|null $competitor_id
 * @param array $competitors
 * @return string
 */
function bjj_get_competitor_name( $competitor_id, $competitors ) {
    if ( $competitor_id && isset( $competitors[ $competitor_id ] ) ) {
        $c = $competitors[ $competitor_id ];
        return trim( $c['first_name'] . ' ' . $c['last_name'] );
    }
    return __( 'Bye', 'bjj' );
}

/**
 * Helper function: Get the winner ID of a match.
 *
 * @param string $match_id
 * @param array $match_results
 * @return int|null
 */
function bjj_get_match_winner( $match_id, $match_results ) {
    return isset( $match_results[ $match_id ] ) ? intval( $match_results[ $match_id ] ) : null;
}
?>

<div class="bjj-tab-content">
    <h2><?php _e( 'Bracket', 'bjj' ); ?></h2>
    <p><?php _e( 'Tournament brackets are generated dynamically based on competitor entries and match results.', 'bjj' ); ?></p>
    
    <?php if ( empty( $bracket_structure ) ) : ?>
        <p><?php _e( 'No competitor data available to generate a bracket.', 'bjj' ); ?></p>
    <?php else : ?>
        <?php foreach ( $bracket_structure as $round => $matches ) : ?>
            <div class="bracket-round <?php echo esc_attr( $round ); ?>">
                <h3><?php echo ucfirst( $round ); ?></h3>
                <ul>
                    <?php foreach ( $matches as $match_id => $match_data ) : ?>
                        <li>
                            <?php if ( isset( $match_data['competitor_a'] ) ) : ?>
                                <!-- First round match -->
                                <?php
                                    $comp_a_id = $match_data['competitor_a'];
                                    $comp_b_id = $match_data['competitor_b'];
                                    $winner_id = bjj_get_match_winner( $match_id, $match_results );
                                    echo esc_html( ucfirst( $match_id ) ) . ': ';
                                    echo esc_html( bjj_get_competitor_name( $comp_a_id, $competitors ) ) . ' vs ' . esc_html( bjj_get_competitor_name( $comp_b_id, $competitors ) );
                                    if ( $winner_id ) {
                                        echo ' — ' . __( 'Winner:', 'bjj' ) . ' ' . esc_html( bjj_get_competitor_name( $winner_id, $competitors ) );
                                    }
                                ?>
                            <?php elseif ( isset( $match_data['winner_of'] ) ) : ?>
