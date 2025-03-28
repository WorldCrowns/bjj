<?php
/**
 * Bracket Tab (Admin) - Fully Dynamic with a Multi-Column Layout
 * Inspired by the style shown in your screenshot (e.g., Master 5 / Male / Black / Ultra Heavy).
 */

// 1. Example bracket info (replace or make dynamic as needed)
$bracket_info = array(
    'division'    => 'Master 5',
    'gender'      => 'Male',
    'belt'        => 'Black',
    'weight_class'=> 'Ultra Heavy',
    'event_name'  => 'Pan IBJJF 2025',
);

// 2. Retrieve competitor data, match results, and generate bracket structure.
$competitors   = get_option( 'bjj_competitors', array() );
$match_results = get_option( 'bjj_match_results', array() );
// bjj_generate_bracket_structure() should already be defined in bjj.php
$bracket_structure = bjj_generate_bracket_structure( $competitors );

/**
 * Helper function: Return competitor name by ID.
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
 * Helper function: Return the winner ID of a match from $match_results.
 */
if ( ! function_exists( 'bjj_get_match_winner' ) ) {
    function bjj_get_match_winner( $match_id, $match_results ) {
        return isset( $match_results[ $match_id ] ) ? intval( $match_results[ $match_id ] ) : null;
    }
}
?>
<!-- You can move this CSS to admin-style.css if desired -->
<style>
    /* Bracket Header (Mimics the screenshot style) */
    .bjj-bracket-header {
        background: #f1f1f1;
        border-bottom: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 20px;
        text-align: center;
    }
    .bjj-bracket-header h2 {
        font-size: 1.3em;
        margin: 0 0 5px 0;
        text-transform: uppercase;
    }
    .bjj-bracket-header p {
        margin: 0;
        font-size: 0.9em;
        color: #666;
    }

    /* Bracket Container */
    .bjj-bracket-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 30px; /* spacing between rounds */
        overflow-x: auto; /* allow horizontal scroll if many rounds */
        padding-bottom: 20px;
    }

    /* Each Round Column */
    .bjj-bracket-round {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-width: 220px; /* enough space for match boxes */
    }
    .bjj-bracket-round h3 {
        text-align: center;
        margin-bottom: 10px;
        text-transform: uppercase;
        font-size: 0.9em;
        color: #555;
    }

    /* Match Box */
    .bjj-match-box {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 10px;
        text-align: center;
        position: relative;
    }
    .bjj-match-box .bjj-competitor {
        display: block;
        margin: 4px 0;
        font-weight: 500;
    }
    .bjj-match-box .bjj-winner {
        color: #0073aa; /* highlight the winner in a different color */
        font-weight: 600;
    }
    .bjj-match-box .bjj-match-id {
        font-size: 0.8em;
        color: #999;
        margin-top: 6px;
    }

</style>

<div class="bjj-bracket-header">
    <h2>
        <?php
        // e.g., "MASTER 5 / MALE / BLACK / ULTRA HEAVY"
        echo esc_html( 
            strtoupper( $bracket_info['division'] ) . ' / ' .
            strtoupper( $bracket_info['gender'] ) . ' / ' .
            strtoupper( $bracket_info['belt'] ) . ' / ' .
            strtoupper( $bracket_info['weight_class'] )
        );
        ?>
    </h2>
    <p>
        <?php echo esc_html( $bracket_info['event_name'] ); ?>
    </p>
</div>

<div class="bjj-tab-content">
    <?php if ( empty( $bracket_structure ) ) : ?>
        <p><?php _e( 'No bracket structure could be generated (no competitors).', 'bjj' ); ?></p>
    <?php else : ?>
        <?php
        // $bracket_structure is typically an array like:
        // [
        //   'round1' => [ 'round1_match_1' => ['competitor_a'=>..., 'competitor_b'=>... ], 'round1_match_2' => ... ],
        //   'round2' => [ 'round2_match_1' => ['winner_of'=>...], ... ],
        //   'round3' => [ ... ],
        // ]
        //
        // We'll display each round as a column in a multi-column layout.
        ?>
        <div class="bjj-bracket-container">
            <?php foreach ( $bracket_structure as $round_name => $matches ) : ?>
                <div class="bjj-bracket-round">
                    <h3><?php echo esc_html( ucfirst( $round_name ) ); ?></h3>
                    <?php foreach ( $matches as $match_id => $match_data ) : ?>
                        <?php
                        // If this is a first-round match, we expect competitor_a, competitor_b.
                        // If this is a subsequent round, we expect 'winner_of' => [ 'some_match_id', 'some_match_id' ].
                        $winner_id = bjj_get_match_winner( $match_id, $match_results );

                        // Decide how to display the competitors:
                        $competitor_lines = array();

                        if ( isset( $match_data['competitor_a'] ) ) {
                            // First round match
                            $comp_a_id = $match_data['competitor_a'];
                            $comp_b_id = $match_data['competitor_b'];
                            // Build lines with possible winner highlight
                            $comp_a_name = bjj_get_competitor_name( $comp_a_id, $competitors );
                            $comp_b_name = bjj_get_competitor_name( $comp_b_id, $competitors );

                            // Mark the winner if any
                            if ( $winner_id === $comp_a_id ) {
                                $comp_a_name = '<span class="bjj-winner">' . esc_html( $comp_a_name ) . '</span>';
                            }
                            if ( $winner_id === $comp_b_id ) {
                                $comp_b_name = '<span class="bjj-winner">' . esc_html( $comp_b_name ) . '</span>';
                            }

                            $competitor_lines[] = $comp_a_name;
                            $competitor_lines[] = $comp_b_name;
                        } elseif ( isset( $match_data['winner_of'] ) ) {
                            // Later round match
                            $source_matches = $match_data['winner_of']; // e.g. ['round1_match_1','round1_match_2']
                            $comp_a_id = bjj_get_match_winner( $source_matches[0], $match_results );
                            $comp_b_id = null;
                            if ( isset( $source_matches[1] ) ) {
                                $comp_b_id = bjj_get_match_winner( $source_matches[1], $match_results );
                            }

                            $comp_a_name = ( $comp_a_id ) ? bjj_get_competitor_name( $comp_a_id, $competitors ) : __( 'TBD', 'bjj' );
                            $comp_b_name = ( $comp_b_id ) ? bjj_get_competitor_name( $comp_b_id, $competitors ) : __( 'TBD', 'bjj' );

                            // Highlight winner
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
                        <div class="bjj-match-box">
                            <?php
                            // Print competitor lines
                            foreach ( $competitor_lines as $line ) {
                                echo '<div class="bjj-competitor">' . $line . '</div>';
                            }
                            // Show match ID at the bottom
                            echo '<div class="bjj-match-id">' . esc_html( $match_id ) . '</div>';
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
