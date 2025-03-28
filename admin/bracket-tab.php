<?php
/**
 * Bracket Tab (Admin) - Fully Dynamic
 * 1) Reads bracket info from the first competitor (division, belt, etc.)
 * 2) Generates bracket structure (via bjj_generate_bracket_structure).
 * 3) Displays multi-column bracket with lines.
 */

// 1. Retrieve competitor data and match results from the DB.
$competitors   = get_option( 'bjj_competitors', array() );
$match_results = get_option( 'bjj_match_results', array() );

// 2. Dynamically derive bracket info from the first competitor.
$bracket_info = array(
    'division'     => 'This will show the Competitor Categories',
    'gender'       => '',
    'belt'         => '',
    'weight_class' => '',
    'event_name'   => 'Pan IBJJF 2025',
);

if ( ! empty( $competitors ) ) {
    // Take the first competitor in the array
    $first_comp = reset( $competitors );
    // Adjust the meta keys below to match your competitor data structure
    $bracket_info['division']     = ! empty( $first_comp['division'] ) ? $first_comp['division'] : 'This will show the Competitor Categories';
    $bracket_info['gender']       = ! empty( $first_comp['gender'] ) ? $first_comp['gender'] : '';
    $bracket_info['belt']         = ! empty( $first_comp['belt'] ) ? $first_comp['belt'] : '';
    $bracket_info['weight_class'] = ! empty( $first_comp['weight_class'] ) ? $first_comp['weight_class'] : '';
}

// 3. Generate bracket structure (make sure bjj_generate_bracket_structure() is defined in bjj.php).
$bracket_structure = bjj_generate_bracket_structure( $competitors );

/**
 * Helper function: get competitor name by ID.
 */
if ( ! function_exists( 'bjj_get_competitor_name' ) ) {
    function bjj_get_competitor_name( $competitor_id, $competitors ) {
        if ( $competitor_id && isset( $competitors[ $competitor_id ] ) ) {
            $c = $competitors[ $competitor_id ];
            return trim( $c['first_name'] . ' ' . $c['last_name'] );
        }
        return __( 'Bye', 'bjj' );
    }
}

/**
 * Helper function: get the winner ID of a match.
 */
if ( ! function_exists( 'bjj_get_match_winner' ) ) {
    function bjj_get_match_winner( $match_id, $match_results ) {
        return isset( $match_results[ $match_id ] ) ? intval( $match_results[ $match_id ] ) : null;
    }
}
?>

<div class="bjj-tab-content">

    <?php if ( empty( $competitors ) ) : ?>
        <!-- No competitors at all -->
        <p><?php _e( 'No competitors found, cannot generate bracket.', 'bjj' ); ?></p>
        <?php return; ?>
    <?php endif; ?>

    <?php if ( empty( $bracket_structure ) ) : ?>
        <!-- If bracket structure is empty for some reason (e.g., 0 or 1 competitor) -->
        <p><?php _e( 'No bracket structure could be generated (not enough competitors).', 'bjj' ); ?></p>
        <?php return; ?>
    <?php endif; ?>

    <!-- We have a bracket structure, so display the bracket header and layout -->

    <style>
    /* =======================
       BRACKET HEADER
    ======================= */
    .bjj-bracket-header {
        background: #f1f1f1;
        border-bottom: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 20px;
        text-align: center;
    }
    .bjj-bracket-header h2 {
        font-size: 1.3em;
        margin: 0 0 5px;
        text-transform: uppercase;
    }
    .bjj-bracket-header p {
        margin: 0;
        font-size: 0.9em;
        color: #666;
    }

    /* =======================
       BRACKET CONTAINER
    ======================= */
    .bjj-bracket-container {
        display: flex;
        gap: 80px; /* Large gap for horizontal lines */
        overflow-x: auto;
        padding-bottom: 20px;
    }

    .bjj-bracket-round {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }
    .bjj-bracket-round h3 {
        margin-bottom: 10px;
        text-transform: uppercase;
        font-size: 0.9em;
        color: #555;
    }

    /* =======================
       MATCH BOX
    ======================= */
    .bjj-match-box {
        position: relative;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px 12px;
        margin: 20px 0;
        width: 180px;
        text-align: center;
    }

    .bjj-competitor {
        margin: 4px 0;
        font-weight: 500;
    }
    .bjj-winner {
        color: #0073aa;
        font-weight: 600;
    }
    .bjj-match-id {
        font-size: 0.75em;
        color: #999;
        margin-top: 5px;
    }

    /* Horizontal line extending to the next round (approximation) */
    .bjj-match-box::after {
        content: "";
        position: absolute;
        top: 50%;
        right: -40px;
        width: 40px;
        height: 2px;
        background: #ccc;
    }
    /* Hide the line for the last column */
    .bjj-bracket-round:last-child .bjj-match-box::after {
        display: none;
    }
    </style>

    <!-- Bracket Header -->
    <div class="bjj-bracket-header">
        <h2>
            <?php
            // e.g. "MASTER 5 / MALE / BLACK / ULTRA HEAVY"
            echo esc_html(
                strtoupper( $bracket_info['division'] ) . ' / ' .
                strtoupper( $bracket_info['gender'] ) . ' / ' .
                strtoupper( $bracket_info['belt'] ) . ' / ' .
                strtoupper( $bracket_info['weight_class'] )
            );
            ?>
        </h2>
        <p><?php echo esc_html( $bracket_info['event_name'] ); ?></p>
    </div>

    <!-- Bracket Layout -->
    <div class="bjj-bracket-container">
        <?php foreach ( $bracket_structure as $round_name => $matches ) : ?>
            <div class="bjj-bracket-round" data-round="<?php echo esc_attr( $round_name ); ?>">
                <h3><?php echo esc_html( ucfirst( $round_name ) ); ?></h3>
                <?php foreach ( $matches as $match_id => $match_data ) : 
                    $winner_id = bjj_get_match_winner( $match_id, $match_results );
                    $competitor_lines = array();

                    // FIRST ROUND MATCH
                    if ( isset( $match_data['competitor_a'] ) ) {
                        $comp_a_id = $match_data['competitor_a'];
                        $comp_b_id = $match_data['competitor_b'];

                        $comp_a_name = bjj_get_competitor_name( $comp_a_id, $competitors );
                        $comp_b_name = bjj_get_competitor_name( $comp_b_id, $competitors );

                        if ( $winner_id === $comp_a_id ) {
                            $comp_a_name = '<span class="bjj-winner">' . esc_html( $comp_a_name ) . '</span>';
                        }
                        if ( $winner_id === $comp_b_id ) {
                            $comp_b_name = '<span class="bjj-winner">' . esc_html( $comp_b_name ) . '</span>';
                        }

                        $competitor_lines[] = $comp_a_name;
                        $competitor_lines[] = $comp_b_name;
                    
                    // SUBSEQUENT ROUND MATCH
                    } elseif ( isset( $match_data['winner_of'] ) ) {
                        $source_matches = $match_data['winner_of'];
                        $comp_a_id = bjj_get_match_winner( $source_matches[0], $match_results );
                        $comp_b_id = null;
                        if ( isset( $source_matches[1] ) ) {
                            $comp_b_id = bjj_get_match_winner( $source_matches[1], $match_results );
                        }

                        $comp_a_name = $comp_a_id ? bjj_get_competitor_name( $comp_a_id, $competitors ) : __( 'TBD', 'bjj' );
                        $comp_b_name = $comp_b_id ? bjj_get_competitor_name( $comp_b_id, $competitors ) : __( 'TBD', 'bjj' );

                        if ( $winner_id === $comp_a_id && $comp_a_id ) {
                            $comp_a_name = '<span class="bjj-winner">' . esc_html( $comp_a_name ) . '</span>';
                        }
                        if ( $winner_id === $comp_b_id && $comp_b_id ) {
                            $comp_b_name = '<span class="bjj-winner">' . esc_html( $comp_b_name ) . '</span>';
                        }

                        $competitor_lines[] = $comp_a_name;
                        $competitor_lines[] = $comp_b_name;
                    }
                    ?>
                    <div class="bjj-match-box" data-match-id="<?php echo esc_attr( $match_id ); ?>">
                        <?php foreach ( $competitor_lines as $line ) : ?>
                            <div class="bjj-competitor"><?php echo wp_kses_post( $line ); ?></div>
                        <?php endforeach; ?>
                        <div class="bjj-match-id"><?php echo esc_html( $match_id ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>

</div>
