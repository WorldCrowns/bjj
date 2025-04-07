<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class BJJ_Competition_AJAX {

    public function __construct() {
        // Category CRUD
        add_action( 'wp_ajax_bjj_add_category', array( $this, 'bjj_add_category' ) );
        add_action( 'wp_ajax_bjj_get_categories', array( $this, 'bjj_get_categories' ) );
        add_action( 'wp_ajax_bjj_delete_category', array( $this, 'bjj_delete_category' ) );
        // Similarly for academies, competitors, mats, etc.
        
        // Reset all data
        add_action( 'wp_ajax_bjj_competition_reset_all', array( $this, 'bjj_competition_reset_all' ) );
    }

    // Example: Add Category
    public function bjj_add_category() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );

        global $wpdb;
        $table = $wpdb->prefix . 'bjj_competition_categories';

        $category_name = sanitize_text_field( $_POST['category_name'] );
        $belt_division = sanitize_text_field( $_POST['belt_division'] );

        $wpdb->insert(
            $table,
            array(
                'category_name' => $category_name,
                'belt_division' => $belt_division
            ),
            array( '%s', '%s' )
        );

        wp_send_json_success( array( 'message' => 'Category added successfully.' ) );
    }

    // Example: Get Categories
    public function bjj_get_categories() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_competition_categories';
        $results = $wpdb->get_results( "SELECT * FROM $table" );
        wp_send_json_success( $results );
    }

    // Example: Delete Category
    public function bjj_delete_category() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );
        global $wpdb;
        $table = $wpdb->prefix . 'bjj_competition_categories';
        $id = intval( $_POST['id'] );
        $wpdb->delete( $table, array( 'id' => $id ), array( '%d' ) );
        wp_send_json_success( array( 'message' => 'Category deleted.' ) );
    }

    // Example: Reset All
    public function bjj_competition_reset_all() {
        check_ajax_referer( 'bjj_competition_nonce', 'nonce' );
        global $wpdb;
        // Truncate the main competition tables
        $tables = array(
            'bjj_competition_categories',
            'bjj_competition_academies',
            'bjj_competition_competitors',
            'bjj_competition_mats',
            'bjj_competition_matches',
            'bjj_competition_results',
            'bjj_competition_podium'
        );
        foreach( $tables as $tbl ) {
            $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}{$tbl}" );
        }

        wp_send_json_success( array( 'message' => 'All data has been reset.' ) );
    }
}
