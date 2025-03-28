<?php
/**
 * Plugin Name: BJJ Tournament Manager
 * Description: Organizes competitor data, manages match results, and dynamically generates a tournament bracket.
 * Version: 1.0
 * Author: Your Name
 * Text Domain: bjj
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin directory and URL constants.
define( 'BJJ_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BJJ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the admin menu file on admin pages.
if ( is_admin() ) {
    require_once BJJ_PLUGIN_DIR . 'admin/admin-menu.php';
}

// Enqueue admin styles and scripts (including the media uploader).
function bjj_enqueue_admin_scripts( $hook ) {
    // Only load our scripts on our plugin pages.
    if ( strpos( $hook, 'bjj' ) === false ) {
        return;
    }
    wp_enqueue_media(); // Now called here, within the admin_enqueue_scripts hook.
    wp_enqueue_style( 'bjj-admin-style', BJJ_PLUGIN_URL . 'assets/css/admin-style.css' );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_script( 'bjj-admin-scripts', BJJ_PLUGIN_URL . 'assets/js/admin-scripts.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0', true );
    wp_localize_script( 'bjj-admin-scripts', 'bjj_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ) );
}
add_action( 'admin_enqueue_scripts', 'bjj_enqueue_admin_scripts' );

/**
 * AJAX handler for live updates.
 */
function bjj_update_live_data() {
    // Placeholder: replace this with dynamic live queue data.
    $response = array(
        'status' => 'success',
        'data'   => 'This is live match data update'
    );
    wp_send_json( $response );
}
add_action( 'wp_ajax_bjj_update_live_data', 'bjj_update_live_data' );

/**
 * Helper function: Generate a dynamic bracket structure based on competitor data.
 *
 * @param array $competitors Array of competitor data, keyed by competitor ID.
 * @return array Bracket structure.
 */
if ( ! function_exists( 'bjj_generate_bracket_structure' ) ) {
    function bjj_generate_bracket_structure( $competitors ) {
        $num_competitors = count( $competitors );
        // Return an empty array if there are no competitors.
        if ( $num_competitors == 0 ) {
            return array();
        }
        
        // Calculate total rounds (next power of 2).
        $total_rounds = ceil( log( $num_competitors, 2 ) );
        $bracket_size = pow( 2, $total_rounds );
        
        // Get competitor IDs; add null for byes if needed.
        $competitor_ids = array_keys( $competitors );
        while ( count( $competitor_ids ) < $bracket_size ) {
            $competitor_ids[] = null;
        }
        
        // Generate first round matches.
        $round1_matches = array();
        for ( $i = 0; $i < $bracket_size; $i += 2 ) {
            $match_id = 'round1_match_' . ( ( $i / 2 ) + 1 );
            $round1_matches[ $match_id ] = array(
                'competitor_a' => $competitor_ids[ $i ],
                'competitor_b' => $competitor_ids[ $i + 1 ],
            );
        }
        
        $bracket_structure = array();
        $bracket_structure['round1'] = $round1_matches;
        
        // Generate subsequent rounds.
        $prev_round = 'round1';
        for ( $round = 2; $round <= $total_rounds; $round++ ) {
            $prev_matches = $bracket_structure[ $prev_round ];
            $current_round_matches = array();
            $prev_match_ids = array_keys( $prev_matches );
            $match_number = 1;
            
            for ( $i = 0; $i < count( $prev_match_ids ); $i += 2 ) {
                $current_match_id = 'round' . $round . '_match_' . $match_number;
                $first_source = $prev_match_ids[ $i ];
                $second_source = isset( $prev_match_ids[ $i + 1 ] ) ? $prev_match_ids[ $i + 1 ] : null;
                $current_round_matches[ $current_match_id ] = array(
                    'winner_of' => array( $first_source, $second_source ),
                );
                $match_number++;
            }
            $bracket_structure[ 'round' . $round ] = $current_round_matches;
            $prev_round = 'round' . $round;
        }
        
        return $bracket_structure;
    }
}
